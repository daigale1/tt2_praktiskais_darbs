<?php

namespace App\Http\Controllers;

use App\Models\TaskMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * All TaskMatch threads the current user is part of —
     * either as the task owner or as the matched helper.
     */
    protected function userMatches()
    {
        $userId = Auth::id();

        return TaskMatch::whereHas('task', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orWhereHas('offer', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['task.user', 'offer.user', 'messages'])
            ->latest('updated_at')
            ->get();
    }

    public function index()
    {
        $matches = $this->userMatches();

        return view('chat.index', compact('matches'));
    }

    public function show(TaskMatch $taskMatch)
    {
        $userId = Auth::id();

        abort_unless(
            $taskMatch->task->user_id === $userId || $taskMatch->offer->user_id === $userId,
            403
        );

        $matches = $this->userMatches();
        $match = $taskMatch->load(['task.user', 'offer.user']);
        $messages = $taskMatch->messages()->with('sender')->oldest()->get();

        return view('chat.show', compact('matches', 'match', 'messages'));
    }

    public function store(Request $request, TaskMatch $taskMatch)
    {
        $userId = Auth::id();

        abort_unless(
            $taskMatch->task->user_id === $userId || $taskMatch->offer->user_id === $userId,
            403
        );

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $taskMatch->messages()->create([
            'sender_id' => $userId,
            'content' => $validated['content'],
        ]);

        return redirect()->route('chat.show', $taskMatch);
    }
}
