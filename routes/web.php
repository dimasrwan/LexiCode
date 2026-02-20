<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SnippetController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- GUEST ROUTES (Bisa diakses tanpa login) ---
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome'); // Pastikan file ini ada di resources/views/welcome.blade.php
});

// Google Auth
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);


// --- PROTECTED ROUTES (Wajib Login) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard & Analytics
    Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/repository', [RepositoryController::class, 'index'])->name('repository.index');

    // Project Controls
    Route::post('/project/store', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/project/{slug}', [ProjectController::class, 'show'])->name('projects.show');
    Route::put('/project/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/project/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Module Controls
    Route::post('/project/{id}/module', [ProjectController::class, 'storeModule'])->name('modules.store');
    Route::delete('/module/{module}', [ProjectController::class, 'destroyModule'])->name('modules.destroy');

    // Snippet Controls
    Route::post('/module/{id}/snippet', [ProjectController::class, 'storeSnippet'])->name('snippets.store');
    Route::patch('/snippet/{snippet}', [SnippetController::class, 'update'])->name('snippets.update');
    Route::delete('/snippet/{snippet}', [ProjectController::class, 'destroySnippet'])->name('snippets.destroy');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';