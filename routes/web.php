<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/admin/login', function () {
    if (Auth::guard('sanctum')->check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('auth.login');
})->name('admin.login');

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [PageController::class, 'users'])->name('admin.users');
    Route::get('/apartments', [PageController::class, 'apartments'])->name('admin.apartments');
    Route::get('/bookings', [PageController::class, 'bookings'])->name('admin.bookings');
    Route::get('/messages', [PageController::class, 'messages'])->name('admin.messages');
    Route::get('/messages/{id}', [PageController::class, 'messageShow'])->name('admin.messages.show');
    Route::get('/reviews', [PageController::class, 'reviews'])->name('admin.reviews');
    Route::get('/settings', [PageController::class, 'settings'])->name('admin.settings');
});
