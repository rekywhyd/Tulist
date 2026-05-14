<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'profile_photo_path',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Global admin check (backward compatibility).
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * All workspaces this user belongs to.
     */
    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'workspace_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Check if the user is admin in a specific workspace.
     */
    public function isWorkspaceAdmin($workspaceId): bool
    {
        return $this->workspaces()
            ->where('workspaces.id', $workspaceId)
            ->wherePivot('role', 'admin')
            ->exists();
    }

    /**
     * Get the user's role in a specific workspace.
     */
    public function workspaceRole($workspaceId): ?string
    {
        $workspace = $this->workspaces()
            ->where('workspaces.id', $workspaceId)
            ->first();

        return $workspace ? $workspace->pivot->role : null;
    }

    /**
     * Check if user belongs to any workspace (to show workspace link in sidebar).
     */
    public function hasAnyWorkspace(): bool
    {
        return $this->workspaces()->exists();
    }
}
