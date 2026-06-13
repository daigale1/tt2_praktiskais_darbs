<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $tasks = Task::with('user')->latest()->paginate(10, ['*'], 'tasks_page');
        $users = User::latest()->paginate(10, ['*'], 'users_page');

        return view('admin.index', compact('tasks', 'users'));
    }

    public function destroyTask(Task $task)
    {
        if ($task->photo_path) {
            Storage::disk('public')->delete($task->photo_path);
        }

        $task->delete();

        return redirect()->route('admin.index')->with('success', 'Task removed.');
    }

    public function blockUser(Request $request, User $user)
    {
        $action = $request->input('action');

        $user->blocked_at = $action === 'unblock' ? null : now();
        $user->save();

        $message = $action === 'unblock' ? 'User unblocked.' : 'User blocked.';

        return redirect()->route('admin.index')->with('success', $message);
    }
}
