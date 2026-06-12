<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\TaskMatchController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

Route::get('/', [FeedController::class, 'index'])->name('home');

// Public profile (anyone can view)
Route::get('/users/{user}', [ProfileController::class, 'public'])->name('profile.public');

Route::middleware('auth')->group(function () {

    // Feed
    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
    Route::post('/feed/skip', [FeedController::class, 'skip'])->name('swipe.store');

    // Tasks
    Route::get('/tasks/mine', [TaskController::class, 'mine'])->name('tasks.mine');
    Route::get('/tasks/completed', [TaskController::class, 'completed'])->name('tasks.completed');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::patch('/tasks/{task}/close', [TaskController::class, 'close'])->name('tasks.close');

    // Offers
    Route::post('/tasks/{task}/offers', [OfferController::class, 'store'])->name('offers.store');
    Route::patch('/offers/{offer}/accept', [OfferController::class, 'accept'])->name('offers.accept');
    Route::patch('/offers/{offer}/decline', [OfferController::class, 'decline'])->name('offers.decline');

    // Matches
    Route::get('/matches', [TaskMatchController::class, 'index'])->name('matches.index');
    Route::patch('/matches/{taskMatch}/complete', [TaskMatchController::class, 'complete'])->name('matches.complete');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{taskMatch}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{taskMatch}', [ChatController::class, 'store'])->name('chat.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Admin
    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::delete('/tasks/{task}', [AdminController::class, 'destroyTask'])->name('tasks.destroy');
        Route::patch('/users/{user}/block', [AdminController::class, 'blockUser'])->name('users.block');
    });
});

require __DIR__.'/auth.php';