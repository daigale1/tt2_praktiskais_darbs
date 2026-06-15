<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Tasks completed because the poster picked *this* user as the helper.
        $helpedTasks = $user->helpedTasks()
            ->where('status', 'completed')
            ->with('user')
            ->latest('updated_at')
            ->get()
            ->map(function ($task) {
                $task->completed_role = 'helper';

                return $task;
            });

        // Tasks this user posted and have since been closed out.
        $postedCompleted = $user->tasks()
            ->where('status', 'completed')
            ->with('helper')
            ->latest('updated_at')
            ->get()
            ->map(function ($task) {
                $task->completed_role = 'poster';

                return $task;
            });

        $completedTasks = $postedCompleted->concat($helpedTasks)
            ->sortByDesc('updated_at')
            ->values();

        return view('profile.show', [
            'user' => $user,
            'publishedCount' => $user->tasks()->count(),
            'offersCount' => $user->offers()->count(),
            'completedCount' => $completedTasks->count(),
            // "My tasks" = anything still open or in-progress (not yet completed/closed).
            'myTasks' => $user->tasks()
                ->whereIn('status', ['open', 'matched'])
                ->withCount(['activeMatches as offers_count'])
                ->latest()
                ->get(),
            'completedTasks' => $completedTasks,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
