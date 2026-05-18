<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-due-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send in-app notifications for tasks approaching their due date (1 day before and 1 hour before)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $sentCount = 0;

        // Fetch tasks that are uncompleted and have a due date >= today
        // We only care about tasks due within the next ~25 hours
        $tasks = Task::with(['workspaces.members'])
            ->where('completed', false)
            ->whereNotNull('due_date')
            ->whereDate('due_date', '>=', $now->toDateString())
            ->whereDate('due_date', '<=', $now->copy()->addDays(2)->toDateString())
            ->get();

        foreach ($tasks as $task) {
            $timeStr = $task->end_time ? $task->end_time->format('H:i:s') : '23:59:59';
            $dueDateTimeStr = $task->due_date->format('Y-m-d') . ' ' . $timeStr;
            $dueDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $dueDateTimeStr);

            if ($now->gt($dueDateTime)) {
                continue; // Already passed
            }

            $diffMinutes = $now->diffInMinutes($dueDateTime, false);
            
            $reminderType = null;
            $title = '';
            $message = '';

            // 1 Day Before Reminder (between 23.5 and 24.5 hours)
            if ($diffMinutes <= 1470 && $diffMinutes >= 1410) {
                $reminderType = '1_day';
                $title = '📅 Due Tomorrow';
                $message = "Task \"{$task->title}\" due tomorrow.";
            } 
            // 1 Hour Before Reminder (between 0 and 65 minutes)
            elseif ($diffMinutes <= 65 && $diffMinutes > 0) {
                $reminderType = '1_hour';
                $timeLabel = $task->end_time ? $task->end_time->format('H:i') : '23:59';
                $title = '⏰ Due in 1 Hour';
                $message = "Task \"{$task->title}\" is due soon at {$timeLabel} (in less than 1 hour).";
            }

            if ($reminderType) {
                $recipientIds = $this->getRecipientIds($task);
                foreach ($recipientIds as $userId) {
                    $exists = Notification::where('user_id', $userId)
                        ->where('type', 'due_reminder')
                        ->where('data->task_id', $task->id)
                        ->where('data->reminder_type', $reminderType)
                        ->exists();

                    if (!$exists) {
                        Notification::create([
                            'user_id' => $userId,
                            'title' => $title,
                            'message' => $message,
                            'type' => 'due_reminder',
                            'is_read' => false,
                            'data' => [
                                'task_id' => $task->id,
                                'task_title' => $task->title,
                                'reminder_type' => $reminderType,
                                'due_date' => $task->due_date->toDateString(),
                            ],
                        ]);
                        $sentCount++;
                    }
                }
            }
        }

        $this->info("Sent {$sentCount} due-date reminder notification(s).");
        return Command::SUCCESS;
    }

    /**
     * Get all user IDs who should receive a reminder for this task.
     * Includes the task creator and all workspace members.
     */
    private function getRecipientIds(Task $task): array
    {
        $ids = collect([$task->user_id]);

        foreach ($task->workspaces as $workspace) {
            foreach ($workspace->members as $member) {
                $ids->push($member->id);
            }
        }

        return $ids->unique()->values()->all();
    }
}
