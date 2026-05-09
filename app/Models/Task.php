<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\TaskAttachment;

class Task extends Model
{

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'priority',
        'completed',
        'completed_at',
        'user_id',
    ];

    protected $casts = [
        'due_date' => 'date:Y-m-d',
        'completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }



    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }
}
