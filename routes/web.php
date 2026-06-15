<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskMatchController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\SwipeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

Route::get('/', function () {
    return redirect()->route('feed.index');
});

Route::middleware('auth')->group(function () {

    // Breeze's auth flow (login/register/email-verification controllers) all
    // redirect to route('dashboard') — alias it to the feed.
    Route::get('/dashboard', function () {
        return redirect()->route('feed.index');
    })->name('dashboard');

    // Feed + swipe
    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
    Route::post('/swipe', [SwipeController::class, 'store'])->name('swipe.store');

    // Tasks
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::match(['put', 'patch'], '/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Poster picks the helper who completed the task from that helper's
    // chat — closes the task and declines every other offer on it.
    Route::patch('/matches/{taskMatch}/complete', [TaskMatchController::class, 'complete'])->name('matches.complete');

    // "My tasks" / "Completed" sidebar links — both land on the profile page
    Route::get('/tasks/mine', [ProfileController::class, 'edit'])->name('tasks.mine');
    Route::get('/tasks/completed', [ProfileController::class, 'edit'])->name('tasks.completed');

    // Profile ("My profile" in sidebar points here)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{taskMatch}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{taskMatch}', [ChatController::class, 'store'])->name('chat.store');

    // Admin
    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::delete('/tasks/{task}', [AdminController::class, 'destroyTask'])->name('tasks.destroy');
        Route::patch('/users/{user}/block', [AdminController::class, 'blockUser'])->name('users.block');
    });
});

require __DIR__.'/auth.php';