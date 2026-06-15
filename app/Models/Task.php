<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'helper_id',
        'title',
        'description',
        'location',
        'lat',
        'lng',
        'photo_path',
        'status',
        'scheduled_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The neighbour who was picked as having completed this task.
     * Only set once the poster closes the task.
     */
    public function helper()
    {
        return $this->belongsTo(User::class, 'helper_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Every match this task has ever had. While a task is open/in-progress
     * it can have several active matches at once — one per neighbour who
     * offered to help — each with its own chat.
     */
    public function matches()
    {
        return $this->hasMany(TaskMatch::class);
    }

    /**
     * Matches that are still "live" — i.e. helpers who offered and are
     * still in the running to be picked for this task.
     */
    public function activeMatches()
    {
        return $this->matches()->where('status', 'active');
    }

    /**
     * Simple "distance" label shown on the feed badge.
     * Falls back to the task's location string until live
     * GPS-based distance is implemented.
     */
    public function getDistanceLabelAttribute(): string
    {
        return $this->location ?? 'Unknown location';
    }

    /**
     * Whether the task is still visible/available in the swipe feed
     * (i.e. neighbours can still offer to help with it).
     */
    public function isOpenForOffers(): bool
    {
        return in_array($this->status, ['open', 'matched'], true);
    }
}
