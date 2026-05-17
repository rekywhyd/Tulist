<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller
{
    /**
     * Get all comments for a task.
     */
    public function index($taskId)
    {
        $task = Task::findOrFail($taskId);

        $comments = $task->comments()
            ->with('user:id,name,profile_photo_path')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'profile_photo_path' => $comment->user->profile_photo_path,
                    ],
                    'is_own' => $comment->user_id === Auth::id(),
                    'created_at' => $comment->created_at->diffForHumans(),
                    'created_at_full' => $comment->created_at->toISOString(),
                ];
            });

        return response()->json($comments);
    }

    /**
     * Store a new comment for a task, parse @mentions and send notifications.
     */
    public function store(Request $request, $taskId)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $task = Task::findOrFail($taskId);
        $user = Auth::user();

        $comment = TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'body' => $request->body,
        ]);

        // Parse @mentions and send notifications
        $this->processMentions($comment, $task, $user);

        $comment->load('user:id,name,profile_photo_path');

        return response()->json([
            'id' => $comment->id,
            'body' => $comment->body,
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
                'profile_photo_path' => $comment->user->profile_photo_path,
            ],
            'is_own' => true,
            'created_at' => $comment->created_at->diffForHumans(),
            'created_at_full' => $comment->created_at->toISOString(),
        ], 201);
    }

    /**
     * Delete a comment (only the author can delete their own comment).
     */
    public function destroy($taskId, $commentId)
    {
        $comment = TaskComment::where('task_id', $taskId)
            ->where('id', $commentId)
            ->firstOrFail();

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Get workspace members for @mention suggestions.
     */
    /**
     * Get workspace members for @mention suggestions.
     */
    public function mentionSuggestions($taskId)
    {
        $task = Task::with('workspaces.members')->findOrFail($taskId);
        $currentUserId = Auth::id();

        $members = collect();

        // Collect unique workspace members (excluding current user)
        foreach ($task->workspaces as $workspace) {
            foreach ($workspace->members as $member) {
                if ($member->id !== $currentUserId && !$members->contains('id', $member->id)) {
                    $members->push([
                        'id' => $member->id,
                        'name' => $member->name,
                        'profile_photo_path' => $member->profile_photo_path,
                    ]);
                }
            }
        }

        return response()->json($members->values());
    }

    /**
     * Get all members from all user's workspaces for @mention suggestions (for New Task).
     */
    public function globalMentionSuggestions()
    {
        $user = Auth::user();
        $currentUserId = Auth::id();

        $members = User::whereHas('workspaces', function ($q) use ($user) {
            $q->whereIn('workspaces.id', $user->workspaces->pluck('id'));
        })
        ->where('id', '!=', $currentUserId)
        ->get(['id', 'name', 'profile_photo_path']);

        return response()->json($members);
    }

    /**
     * Process @mentions in a comment body and create notifications.
     */
    public function processMentions(TaskComment $comment, Task $task, User $mentioner): void
    {
        // Get all workspace members for this task
        $workspaceIds = $task->workspaces()->pluck('workspaces.id');
        if ($workspaceIds->isEmpty()) {
            return;
        }

        // Fetch active members excluding the mentioner
        $members = User::whereHas('workspaces', function ($q) use ($workspaceIds) {
            $q->whereIn('workspaces.id', $workspaceIds);
        })->where('id', '!=', $mentioner->id)->get();

        $mentionedUsers = collect();

        // Check if the comment body contains @MemberName
        foreach ($members as $member) {
            $pattern = '/@' . preg_quote($member->name, '/') . '(?!\w)/i';
            if (preg_match($pattern, $comment->body)) {
                $mentionedUsers->push($member);
            }
        }

        if ($mentionedUsers->isEmpty()) {
            return;
        }

        foreach ($mentionedUsers as $mentionedUser) {
            Notification::create([
                'user_id' => $mentionedUser->id,
                'title' => '💬 Mentions in Comments',
                'message' => "{$mentioner->name} mentioned you in a comment on a task \"{$task->title}\".",
                'type' => 'mention',
                'is_read' => false,
                'data' => [
                    'task_id' => $task->id,
                    'task_title' => $task->title,
                    'comment_id' => $comment->id,
                    'mentioned_by' => $mentioner->name,
                    'mentioned_by_id' => $mentioner->id,
                ],
            ]);
        }
    }
}
