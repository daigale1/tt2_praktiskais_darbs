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

        // Right swipe: only allowed while the task is still accepting helpers.
        // (The feed already filters these out, but guard against stale pages
        // or resubmits hitting a task that was just completed/closed.)
        abort_unless($task->isOpenForOffers(), 403);

        // Auto-create an accepted offer + an active match, and jump straight
        // into the chat. The task itself stays in the pool (status becomes
        // "matched" / in-progress rather than disappearing) so other
        // neighbours can keep offering to help — the poster picks who
        // actually did the job later on.
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

        if ($task->status === 'open') {
            $task->update(['status' => 'matched']);
        }

        return redirect()->route('chat.show', $match);
    }
}
