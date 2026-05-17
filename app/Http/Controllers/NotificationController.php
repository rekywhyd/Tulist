<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\WorkspaceInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->get();
        return response()->json($notifications);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $notification = Notification::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'message' => $request->message,
            'is_read' => false,
            'type' => $request->input('type', 'general'),
            'data' => $request->input('data'),
        ]);

        return response()->json([
            'id' => $notification->id,
            'title' => $notification->title,
            'message' => $notification->message,
            'is_read' => $notification->is_read,
            'type' => $notification->type,
            'data' => $notification->data,
            'created_at' => $notification->created_at->toISOString(),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        return response()->json($notification);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);
        return response()->json($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();
        return response()->json(['message' => 'Notification deleted']);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    /**
     * Delete all notifications.
     */
    public function deleteAll()
    {
        Auth::user()->notifications()->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Accept a workspace invitation from a notification.
     */
    public function acceptInvitation(Request $request, string $notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);

        if ($notification->type !== 'workspace_invitation') {
            return response()->json(['error' => 'Invalid notification type.'], 422);
        }

        $invitationId = $notification->data['invitation_id'] ?? null;
        if (!$invitationId) {
            return response()->json(['error' => 'No invitation found.'], 422);
        }

        $invitation = WorkspaceInvitation::where('id', $invitationId)
            ->where('status', 'pending')
            ->first();

        if (!$invitation) {
            $notification->update(['is_read' => true]);
            return response()->json(['error' => 'Invitation no longer valid or already processed.'], 422);
        }

        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            $notification->update(['is_read' => true]);
            return response()->json(['error' => 'Invitation has expired.'], 422);
        }

        $user = Auth::user();
        $workspace = $invitation->workspace;

        if (!$workspace->members()->where('user_id', $user->id)->exists()) {
            $workspace->members()->attach($user->id, ['role' => 'member']);
        }

        $invitation->update(['status' => 'accepted']);
        $notification->update([
            'is_read' => true,
            'message' => "You joined \"{$workspace->name}\"!",
        ]);

        return response()->json([
            'success' => true,
            'workspace_id' => $workspace->id,
            'message' => "You have joined \"{$workspace->name}\"!",
        ]);
    }

    /**
     * Decline a workspace invitation from a notification.
     */
    public function declineInvitation(Request $request, string $notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);

        if ($notification->type !== 'workspace_invitation') {
            return response()->json(['error' => 'Invalid notification type.'], 422);
        }

        $invitationId = $notification->data['invitation_id'] ?? null;
        if ($invitationId) {
            $invitation = WorkspaceInvitation::find($invitationId);
            if ($invitation && $invitation->status === 'pending') {
                $invitation->update(['status' => 'declined']);
            }
        }

        $notification->update([
            'is_read' => true,
            'message' => 'Invitation declined.',
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread notification count.
     */
    public function unreadCount()
    {
        $count = Auth::user()->notifications()->where('is_read', false)->count();
        return response()->json(['count' => $count]);
    }
}
