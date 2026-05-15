<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\WorkspaceInvitation;
use App\Models\User;
use App\Mail\WorkspaceInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class WorkspaceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $workspaces = $user->workspaces()
            ->withCount('members')
            ->withPivot('last_viewed_at', 'role')
            ->orderBy('name')
            ->get();

        $selectedWorkspace = null;
        $members = collect();
        $userRole = null;

        if ($request->has('workspace_id')) {
            $selectedWorkspace = $user->workspaces()->where('workspaces.id', $request->workspace_id)->first();
        }

        $tasks = collect();

        if ($selectedWorkspace) {
            $members = $selectedWorkspace->members()->orderBy('name')->get();
            $userRole = $user->workspaceRole($selectedWorkspace->id);
            $tasks = $selectedWorkspace->tasks()->orderBy('tasks.created_at', 'desc')->get()->map(function($task) use ($user) {
                $task->can_modify = $task->canUserModify($user);
                return $task;
            });

            // Update last_viewed_at for the current user in this workspace
            $selectedWorkspace->members()->updateExistingPivot($user->id, ['last_viewed_at' => now()]);
        }

        // Add unread tasks count to each workspace in the list
        foreach ($workspaces as $ws) {
            $lastViewedAt = $ws->pivot->last_viewed_at;
            $unreadQuery = $ws->tasks()->where('completed', false);
            
            if ($lastViewedAt) {
                $unreadQuery->where('tasks.created_at', '>', $lastViewedAt);
            }
            
            $ws->unread_tasks_count = $unreadQuery->count();
        }

        return view('workspace', compact('workspaces', 'selectedWorkspace', 'members', 'userRole', 'tasks'));
    }

    /**
     * Create a new workspace. Creator becomes admin automatically.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        $workspace = Workspace::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => $user->id,
        ]);

        // Auto-assign creator as admin
        $workspace->members()->attach($user->id, ['role' => 'admin']);

        return redirect()->route('workspace', ['workspace_id' => $workspace->id])
            ->with('success', 'Workspace created successfully!');
    }

    /**
     * Invite a member to workspace via email.
     */
    public function invite(Request $request, $workspaceId)
    {
        $request->validate([
            'emails' => 'required|string',
        ]);

        $user = Auth::user();
        $workspace = Workspace::findOrFail($workspaceId);

        // Check if user is admin in this workspace
        if (!$user->isWorkspaceAdmin($workspaceId)) {
            return response()->json(['error' => 'Only admins can invite members.'], 403);
        }

        // Parse emails from string (supports comma, semicolon, newline, space)
        $emailList = preg_split('/[\s,;]+/', $request->emails, -1, PREG_SPLIT_NO_EMPTY);
        $emailList = array_unique(array_map('trim', $emailList));

        $invited = [];
        $errors = [];

        foreach ($emailList as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email: $email";
                continue;
            }

            // Check if already a member
            $existingMember = $workspace->members()->where('users.email', $email)->first();
            if ($existingMember) {
                $errors[] = "$email is already a member.";
                continue;
            }

            // Check for pending invitation
            $existingInvitation = WorkspaceInvitation::where('workspace_id', $workspaceId)
                ->where('email', $email)
                ->where('status', 'pending')
                ->first();

            if ($existingInvitation) {
                $errors[] = "Invitation already pending for $email.";
                continue;
            }

            // Create invitation
            $invitation = WorkspaceInvitation::create([
                'workspace_id' => $workspaceId,
                'invited_by' => $user->id,
                'email' => $email,
                'token' => Str::random(64),
                'status' => 'pending',
                'expires_at' => now()->addDays(7),
            ]);

            // Send invitation email
            try {
                Mail::to($email)->send(new WorkspaceInvitationMail($workspace, $invitation));
                $invited[] = $email;
            } catch (\Exception $e) {
                $errors[] = "Failed to send email to $email.";
            }
        }

        if (empty($invited)) {
            return response()->json(['error' => implode(' ', $errors)], 422);
        }

        $successMessage = count($invited) . " invitation(s) sent successfully.";
        if (!empty($errors)) {
            $successMessage .= " Note: " . implode(' ', $errors);
        }

        return response()->json(['success' => $successMessage]);
    }

    /**
     * Accept a workspace invitation via token.
     */
    public function acceptInvitation($token)
    {
        $invitation = WorkspaceInvitation::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            return redirect()->route('home')->with('error', 'This invitation has expired.');
        }

        $user = Auth::user();

        if (!$user) {
            // Store invitation token in session and redirect to login
            session(['pending_invitation' => $token]);
            return redirect()->route('login')->with('info', 'Please log in to accept the invitation.');
        }

        // Check if email matches
        if ($user->email !== $invitation->email) {
            return redirect()->route('home')->with('error', 'This invitation was sent to a different email address.');
        }

        // Add user to workspace as member
        $workspace = $invitation->workspace;
        if (!$workspace->members()->where('user_id', $user->id)->exists()) {
            $workspace->members()->attach($user->id, ['role' => 'member']);
        }

        $invitation->update(['status' => 'accepted']);

        return redirect()->route('workspace', ['workspace_id' => $workspace->id])
            ->with('success', "You have joined \"{$workspace->name}\"!");
    }

    /**
     * Update a member's role in the workspace (admin only).
     */
    public function updateRole(Request $request, $workspaceId, $userId)
    {
        $request->validate([
            'role' => 'required|in:admin,member',
        ]);

        $currentUser = Auth::user();
        $workspace = Workspace::findOrFail($workspaceId);

        // Check if current user is admin
        if (!$currentUser->isWorkspaceAdmin($workspaceId)) {
            return response()->json(['error' => 'Only admins can change roles.'], 403);
        }

        // Prevent removing the last admin
        if ($request->role === 'member') {
            $adminCount = $workspace->admins()->count();
            $targetIsAdmin = $workspace->members()
                ->where('user_id', $userId)
                ->wherePivot('role', 'admin')
                ->exists();

            if ($adminCount <= 1 && $targetIsAdmin) {
                return response()->json(['error' => 'Cannot remove the last admin from the workspace.'], 422);
            }
        }

        // Update role
        $workspace->members()->updateExistingPivot($userId, ['role' => $request->role]);

        return response()->json(['success' => 'Role updated successfully!']);
    }

    /**
     * Remove a member from workspace (admin only).
     */
    public function removeMember(Request $request, $workspaceId, $userId)
    {
        $currentUser = Auth::user();
        $workspace = Workspace::findOrFail($workspaceId);

        // Check admin
        if (!$currentUser->isWorkspaceAdmin($workspaceId)) {
            return response()->json(['error' => 'Only admins can remove members.'], 403);
        }

        // Prevent removing self if last admin
        if ($currentUser->id == $userId) {
            $adminCount = $workspace->admins()->count();
            if ($adminCount <= 1) {
                return response()->json(['error' => 'Cannot remove yourself as the last admin.'], 422);
            }
        }

        // Remove from workspace
        $workspace->members()->detach($userId);

        return response()->json(['success' => 'Member removed successfully!']);
    }

    /**
     * Show workspace details.
     */
    public function show($workspaceId)
    {
        $workspace = Workspace::with('owner')->findOrFail($workspaceId);
        
        return response()->json([
            'name' => $workspace->name,
            'description' => $workspace->description,
            'owner' => $workspace->owner->name,
            'created_at' => $workspace->created_at->format('M d, Y'),
            'members_count' => $workspace->members()->count(),
            'tasks_count' => $workspace->tasks()->count(),
        ]);
    }

    /**
     * Update workspace details.
     */
    public function update(Request $request, $workspaceId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $workspace = Workspace::findOrFail($workspaceId);

        if (!$user->isWorkspaceAdmin($workspaceId)) {
            return redirect()->back()->with('error', 'Only admins can update workspace details.');
        }

        $workspace->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('workspace', ['workspace_id' => $workspace->id])
            ->with('success', 'Workspace updated successfully!');
    }

    /**
     * Delete a workspace (owner only).
     */
    public function destroy($workspaceId)
    {
        $user = Auth::user();
        $workspace = Workspace::findOrFail($workspaceId);

        if ($workspace->owner_id !== $user->id) {
            return response()->json(['error' => 'Only the workspace owner can delete it.'], 403);
        }

        $workspace->delete();

        return redirect()->route('workspace')->with('success', 'Workspace deleted successfully!');
    }

    /**
     * Leave a workspace (for non-owners).
     */
    public function leave($workspaceId)
    {
        $user = Auth::user();
        $workspace = Workspace::findOrFail($workspaceId);

        // Cannot leave if owner
        if ($workspace->owner_id === $user->id) {
            return response()->json(['error' => 'Owners cannot leave their own workspace. Transfer ownership or delete it.'], 422);
        }

        // Check if last admin
        if ($user->isWorkspaceAdmin($workspaceId)) {
            $adminCount = $workspace->admins()->count();
            if ($adminCount <= 1) {
                return response()->json(['error' => 'You are the last admin. Promote another member first.'], 422);
            }
        }

        $workspace->members()->detach($user->id);

        return redirect()->route('workspace')->with('success', 'You have left the workspace.');
    }
}
