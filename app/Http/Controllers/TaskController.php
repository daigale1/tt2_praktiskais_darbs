<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:400'],
            'location' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['nullable', 'date'],
            'photo_path' => ['nullable', 'image', 'max:5120'],
        ]);

        $task = new Task([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'scheduled_at' => $validated['scheduled_at'] ?? null,
        ]);

        if ($request->hasFile('photo_path')) {
            $task->photo_path = $request->file('photo_path')->store('tasks', 'public');
        }

        $task->save();

        return redirect()->route('feed.index')->with('success', 'Task posted!');
    }

    public function edit(Task $task)
    {
        abort_unless($task->user_id === Auth::id(), 403);

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        abort_unless($task->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:400'],
            'location' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['nullable', 'date'],
            'photo_path' => ['nullable', 'image', 'max:5120'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        $task->title = $validated['title'];
        $task->description = $validated['description'];
        $task->location = $validated['location'];
        $task->scheduled_at = $validated['scheduled_at'] ?? null;

        if ($request->boolean('remove_photo') && $task->photo_path) {
            Storage::disk('public')->delete($task->photo_path);
            $task->photo_path = null;
        }

        if ($request->hasFile('photo_path')) {
            if ($task->photo_path) {
                Storage::disk('public')->delete($task->photo_path);
            }
            $task->photo_path = $request->file('photo_path')->store('tasks', 'public');
        }

        $task->save();

        return redirect()->route('profile.edit')->with('success', 'Task updated!');
    }

    public function destroy(Task $task)
    {
        abort_unless($task->user_id === Auth::id(), 403);

        if ($task->photo_path) {
            Storage::disk('public')->delete($task->photo_path);
        }

        $task->delete();

        return redirect()->route('profile.edit')->with('success', 'Task deleted.');
    }
}
