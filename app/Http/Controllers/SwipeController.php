<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Task;
use App\Models\TaskMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwipeController extends Controller
{
    /**
     * Handle a left (skip) or right (offer to help) swipe on the feed.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_id' => ['required', 'exists:tasks,id'],
            'direction' => ['required', 'in:left,right'],
        ]);

        $task = Task::findOrFail($validated['task_id']);

        if ($validated['direction'] === 'left') {
            $skipped = session('skipped_task_ids', []);
            $skipped[] = $task->id;
            session(['skipped_task_ids' => $skipped]);

            return redirect()->route('feed.index');
        }

        // Right swipe: auto-create an accepted offer + an active match,
        // mark the task as matched, and jump straight into the chat.
        $offer = Offer::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'status' => 'accepted',
        ]);

        $match = TaskMatch::create([
            'task_id' => $task->id,
            'offer_id' => $offer->id,
            'status' => 'active',
            'matched_at' => now(),
        ]);

        $task->update(['status' => 'matched']);

        return redirect()->route('chat.show', $match);
    }
}
