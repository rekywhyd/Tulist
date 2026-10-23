<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskDueReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $task;
    public $reminderType;
    public $dueFormatted;
    public $taskUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Task $task, string $reminderType)
    {
        $this->user = $user;
        $this->task = $task;
        $this->reminderType = $reminderType; // '1_day' or '1_hour'

        $timeStr = $task->end_time ? $task->end_time->format('H:i') : null;
        $dateStr = $task->due_date ? $task->due_date->format('M d, Y') : '';

        if ($timeStr) {
            $this->dueFormatted = "{$dateStr} at {$timeStr}";
        } else {
            $this->dueFormatted = $dateStr;
        }

        $this->taskUrl = url('/home?open_task=' . $task->id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $prefix = $this->reminderType === '1_hour' ? '⏰ [Reminder 1 Hour]' : '📅 [Reminder Tomorrow]';
        $subject = "{$prefix} Task: \"{$this->task->title}\" is due soon";

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.task-reminder',
        );
    }
}
