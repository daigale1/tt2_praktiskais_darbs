<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskMatch extends Model
{
    protected $fillable = ['task_id', 'offer_id', 'status', 'matched_at'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // The person who posted the task
    public function poster()
    {
        return $this->task->user;
    }

    // The person who offered to help — TaskMatch → Offer → User
    public function helper()
    {
        return $this->offer->user;
    }

    public function otherUser($currentUserId)
    {
        return $this->poster()->id === $currentUserId
            ? $this->helper()
            : $this->poster();
    }
}
