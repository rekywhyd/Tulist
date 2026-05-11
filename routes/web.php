<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/employees', [App\Http\Controllers\TaskController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('employees');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/home', function () {
    $user = Auth::user();
    $tasks = Task::where('user_id', $user->id)->get();


    $todayTasks = $tasks->filter(function ($task) {
        return $task->due_date->isToday() && !$task->completed;
    });
    $tomorrowTasks = $tasks->filter(function ($task) {
        return $task->due_date->isTomorrow() && !$task->completed;
    });
    $upcomingTasks = $tasks->filter(function ($task) {
        return $task->due_date->gt(now()->addDay()) && !$task->completed;
    });
    $overdueTasks = $tasks->filter(function ($task) {
        return $task->due_date->lt(now()->startOfDay()) && !$task->completed;
    });
    $historyTasks = $tasks->filter(function ($task) {
        return $task->completed;
    });

    $todayCount = $todayTasks->count();
    $tomorrowCount = $tomorrowTasks->count();
    $upcomingCount = $upcomingTasks->count();
    $overdueCount = $overdueTasks->count();

    // Set session alert if there are tasks due today
    if ($todayCount > 0) {
        session(['show_alert' => true]);
    }

    return view('home', compact('todayTasks', 'tomorrowTasks', 'upcomingTasks', 'overdueTasks', 'historyTasks', 'todayCount', 'tomorrowCount', 'upcomingCount', 'overdueCount', 'user'));
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

    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{id}/duplicate', [TaskController::class, 'duplicate'])->name('tasks.duplicate');


    Route::resource('notifications', NotificationController::class);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    Route::post('/clear-alert', function () {
        session()->forget('show_alert');
        return response()->json(['success' => true]);
    })->name('clear.alert');
});

Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

require __DIR__ . '/auth.php';
