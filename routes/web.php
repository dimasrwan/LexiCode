<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Halaman Utama: Menampilkan daftar proyek LexiCode
Route::get('/', [ProjectController::class, 'index'])->name('dashboard');
Route::get('/project/{slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::post('/project/store', [ProjectController::class, 'store'])->name('projects.store');
Route::delete('/project/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
Route::put('/project/{id}', [ProjectController::class, 'update'])->name('projects.update');
Route::get('/project/{slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::post('/project/{id}/module', [ProjectController::class, 'storeModule'])->name('modules.store');
Route::post('/module/{id}/snippet', [ProjectController::class, 'storeSnippet'])->name('snippets.store');

// Route untuk fitur Profile (Bawaan Laravel Breeze/Starter Kit)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';