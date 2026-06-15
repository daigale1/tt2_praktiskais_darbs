<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'location',
        'lat',
        'lng',
        'age',
        'role',
        'blocked_at',
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
            'blocked_at' => 'datetime',
        ];
    }

    /**
     * Check whether the user has the given role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Tasks (posted by other people) that this user was picked to have
     * completed as the helper.
     */
    public function helpedTasks()
    {
        return $this->hasMany(Task::class, 'helper_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function getPublishedTasksCountAttribute(): int
    {
        return $this->tasks()->count();
    }

    public function getOffersCountAttribute(): int
    {
        return $this->offers()->count();
    }

    /**
     * Total tasks this user has completed — either as the poster whose
     * task got finished, or as the helper who was picked to do it.
     */
    public function getCompletedTasksCountAttribute(): int
    {
        return $this->tasks()->where('status', 'completed')->count()
            + $this->helpedTasks()->where('status', 'completed')->count();
    }
}
