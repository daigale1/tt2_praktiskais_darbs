<?php

namespace App\Http\Controllers;

use App\Models\TaskMatch;
use Illuminate\Support\Facades\Auth;

class TaskMatchController extends Controller
{
    /**
     * The poster picks the helper behind this match as the one who
     * completed the task, closes the task out, and declines every
     * other active offer on it.
     *
     * Called from the chat header (one button per conversation) — by
     * marking a task complete from a specific helper's chat, the poster
     * is choosing that helper.
     */
    public function complete(TaskMatch $taskMatch)
    {
        $task = $taskMatch->task;

        abort_unless($task->user_id === Auth::id(), 403);
        abort_unless($task->isOpenForOffers(), 403);
        abort_unless($taskMatch->status === 'active', 403);

        // This helper did the job — close the task and remember who helped.
        $taskMatch->update(['status' => 'completed']);

        $task->update([
            'status' => 'completed',
            'helper_id' => $taskMatch->offer->user_id,
        ]);

        // Every other neighbour who offered to help is now declined —
        // their match/chat is closed out too.
        $task->matches()
            ->where('id', '!=', $taskMatch->id)
            ->where('status', 'active')
            ->get()
            ->each(function (TaskMatch $other) {
                $other->update(['status' => 'cancelled']);
                $other->offer?->update(['status' => 'declined']);
            });

        return redirect()->route('chat.show', $taskMatch)
            ->with('success', 'Task marked as completed!');
    }
}
