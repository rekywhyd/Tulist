<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\TaskAttachment;

class Task extends Model
{

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'start_time',
        'end_time',
        'priority',
        'completed',
        'completed_at',
        'user_id',
    ];

    protected $casts = [
        'due_date' => 'date:Y-m-d',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'task_workspace')->withTimestamps();
    }



    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(\App\Models\TaskComment::class);
    }

    /**
     * Check if a user can edit, duplicate or delete this task.
     * Rule: User is the creator OR User is an admin in any of the workspaces this task belongs to.
     */
    public function canUserModify($user): bool
    {
        if (!$user) return false;
        
        // Creator can always modify
        if ($this->user_id === $user->id) return true;
        
        // Check if user is admin in any of the workspaces this task belongs to
        return $this->workspaces()->whereHas('members', function($q) use ($user) {
            $q->where('users.id', $user->id)
              ->where('workspace_user.role', 'admin');
        })->exists();
    }

    /**
     * Scope a query to order tasks by the specified hierarchy:
     * 1. Status: Completed first, then Not Completed (completed DESC)
     * 2. Deadline & Urgency: due_date (present first, then ASC), start_time (present first, then ASC), end_time (present first, then ASC)
     * 3. Classification / Importance: Priority (Urgent -> High -> Normal -> Low)
     */
    public function scopeOrderedHierarchy($query)
    {
        return $query
            // Level 1: Status (Not Completed first, Completed at the bottom)
            ->orderBy('completed', 'asc')
            
            // Level 2: Deadline & Urgency
            ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END ASC')
            ->orderBy('due_date', 'asc')
            ->orderByRaw('CASE WHEN start_time IS NULL THEN 1 ELSE 0 END ASC')
            ->orderBy('start_time', 'asc')
            ->orderByRaw('CASE WHEN end_time IS NULL THEN 1 ELSE 0 END ASC')
            ->orderBy('end_time', 'asc')
            
            // Level 3: Priority / Importance
            ->orderByRaw("CASE priority 
                WHEN 'Urgent' THEN 1 
                WHEN 'High' THEN 2 
                WHEN 'Normal' THEN 3 
                WHEN 'Low' THEN 4 
                ELSE 5 
            END ASC");
    }
}

