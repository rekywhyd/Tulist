<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskComment extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'body',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Parse @mentions from the comment body and return an array of mentioned usernames.
     */
    public function getMentionedNames(): array
    {
        preg_match_all('/@([\w\s]+?)(?=\s@|$|\.|,|!|\?|;|:)/', $this->body, $matches);
        return array_map('trim', $matches[1] ?? []);
    }
}
