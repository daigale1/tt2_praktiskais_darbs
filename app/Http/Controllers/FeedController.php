<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $offeredTaskIds = $user->offers()->pluck('task_id');
        $skippedIds = collect(session('skipped_task_ids', []));
        $excludedIds = $offeredTaskIds->merge($skippedIds)->unique();

        $tasks = Task::where('status', 'open')
            ->where('user_id', '!=', $user->id)
            ->whereNotIn('id', $excludedIds)
            ->with('user')
            ->latest()
            ->get();

        return view('tasks.feed', compact('tasks'));
    }
}
