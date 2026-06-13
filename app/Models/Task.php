<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id',
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

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function match()
    {
        return $this->hasOne(TaskMatch::class);
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
}
