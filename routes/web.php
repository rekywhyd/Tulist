<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\TaskCommentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

Route::get('/', function () {
    return view('welcome');
});

// Workspace routes (accessible to all authenticated users)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/workspace', [WorkspaceController::class, 'index'])->name('workspace');
    Route::post('/workspace', [WorkspaceController::class, 'store'])->name('workspace.store');
    Route::get('/workspace/{workspaceId}', [WorkspaceController::class, 'show'])->name('workspace.show');
    Route::put('/workspace/{workspaceId}', [WorkspaceController::class, 'update'])->name('workspace.update');
    Route::delete('/workspace/{workspaceId}', [WorkspaceController::class, 'destroy'])->name('workspace.destroy');
    Route::post('/workspace/{workspaceId}/invite', [WorkspaceController::class, 'invite'])->name('workspace.invite');
    Route::put('/workspace/{workspaceId}/member/{userId}/role', [WorkspaceController::class, 'updateRole'])->name('workspace.updateRole');
    Route::delete('/workspace/{workspaceId}/member/{userId}', [WorkspaceController::class, 'removeMember'])->name('workspace.removeMember');
    Route::post('/workspace/{workspaceId}/leave', [WorkspaceController::class, 'leave'])->name('workspace.leave');
    Route::post('/workspace/{workspaceId}/mark-read', [WorkspaceController::class, 'markAsRead'])->name('workspace.markRead');
});

// Accept invitation (works for both authenticated and guest users)
Route::get('/workspace/invitation/{token}/accept', [WorkspaceController::class, 'acceptInvitation'])
    ->middleware(['auth', 'verified'])
    ->name('workspace.acceptInvitation');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/home', function () {
    $user = Auth::user();
    $tasks = Task::with('workspaces')
        ->where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('workspaces', function($q) use ($user) {
                      $q->whereIn('workspaces.id', $user->workspaces->pluck('id'));
                  });
        })
        ->get()->each(function($t) use ($user) {
            $t->can_modify = $t->canUserModify($user);
        });


    $todayTasks = $tasks->filter(function ($task) {
        if ($task->completed || !$task->due_date->isToday()) return false;
        if ($task->end_time && now()->format('H:i:s') > $task->end_time->format('H:i:s')) return false;
        return true;
    });
    $tomorrowTasks = $tasks->filter(function ($task) {
        return $task->due_date->isTomorrow() && !$task->completed;
    });
    $upcomingTasks = $tasks->filter(function ($task) {
        return $task->due_date->gt(now()->addDay()) && !$task->completed;
    });
    $overdueTasks = $tasks->filter(function ($task) {
        if ($task->completed) return false;
        if ($task->due_date->lt(now()->startOfDay())) return true;
        if ($task->due_date->isToday() && $task->end_time && now()->format('H:i:s') > $task->end_time->format('H:i:s')) return true;
        return false;
    });
    $historyTasks = $tasks->filter(function ($task) {
        return $task->completed;
    });

    $todayCount = $todayTasks->count();
    $tomorrowCount = $tomorrowTasks->count();
    // Set session alert if there are tasks due today
    if ($todayCount > 0) {
        session(['show_alert' => true]);
    }

    $upcomingCount = $upcomingTasks->count();
    $overdueCount = $overdueTasks->count();

    $workspaces = $user->workspaces()->get();

    return view('home', compact('todayTasks', 'tomorrowTasks', 'upcomingTasks', 'overdueTasks', 'historyTasks', 'todayCount', 'tomorrowCount', 'upcomingCount', 'overdueCount', 'user', 'workspaces'));
})->middleware(['auth', 'verified'])->name('home');

Route::get('/schedule', [App\Http\Controllers\TaskController::class, 'schedule'])
    ->middleware(['auth', 'verified'])
    ->name('schedule');
Route::post('/schedule', [App\Http\Controllers\TaskController::class, 'schedule'])
    ->middleware(['auth', 'verified'])
    ->name('schedule');

Route::get('/help', [PageController::class, 'help'])
    ->name('help')
    ->middleware('auth');
Route::post('/submit-help', [PageController::class, 'submitHelp'])
    ->name('help.submit')
    ->middleware('auth');
Route::get('/privacy', [PageController::class, 'privacy'])
    ->name('privacy')
    ->middleware('auth');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Profile photo upload
    Route::post('/profile/photo/upload', [ProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
    // Profile photo delete
    Route::delete('/profile/photo/delete', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

    Route::get('/tasks/search', [TaskController::class, 'search'])->name('tasks.search');
    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{id}/duplicate', [TaskController::class, 'duplicate'])->name('tasks.duplicate');


    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');
    Route::post('/notifications/{notificationId}/accept-invitation', [NotificationController::class, 'acceptInvitation'])->name('notifications.acceptInvitation');
    Route::post('/notifications/{notificationId}/decline-invitation', [NotificationController::class, 'declineInvitation'])->name('notifications.declineInvitation');
    Route::resource('notifications', NotificationController::class);

    Route::get('/history/report', [TaskController::class, 'historyReport'])->name('history.report');
    Route::delete('/history/clear', [TaskController::class, 'clearHistory'])->name('history.clear');

    // Task Comments & @Mentions
    Route::get('/tasks/{taskId}/comments', [TaskCommentController::class, 'index'])->name('task.comments.index');
    Route::post('/tasks/{taskId}/comments', [TaskCommentController::class, 'store'])->name('task.comments.store');
    Route::delete('/tasks/{taskId}/comments/{commentId}', [TaskCommentController::class, 'destroy'])->name('task.comments.destroy');
    Route::get('/tasks/{taskId}/mention-suggestions', [TaskCommentController::class, 'mentionSuggestions'])->name('task.mentionSuggestions');
    Route::get('/global-mention-suggestions', [TaskCommentController::class, 'globalMentionSuggestions'])->name('global.mentionSuggestions');

    Route::post('/clear-alert', function () {
        session()->forget('show_alert');
        return response()->json(['success' => true]);
    })->name('clear.alert');
});

Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

require __DIR__ . '/auth.php';
