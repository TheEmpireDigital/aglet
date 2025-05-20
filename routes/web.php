<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;


// Public routes
Route::get('/', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

// Authentication Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('register.store');

// Password Reset Routes
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'create'])
    ->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'store'])
    ->name('password.update');

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/favorites', [MovieController::class, 'favorites'])
        ->name('favorites.index');
    Route::post('/favorites/{movie}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{movie}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

});

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
