<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WebAuthController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Welcome/Home page


// // Authentication pages - GET routes (show forms)
// Route::get('/login', function () {
//     return view('auth.login');
// })->name('login');

// Route::get('/register', function () {
//     return view('auth.register');
// })->name('register');

// // Authentication - POST routes (handle form submission)
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register', [AuthController::class, 'register']);

// // API Authentication routes (for AJAX/API calls)
// Route::post('/api/login', [AuthController::class, 'login']);
// Route::post('/api/register', [AuthController::class, 'register']);
// Route::post('/api/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// /*
// |--------------------------------------------------------------------------
// | Protected Admin Routes (Require Authentication)
// |--------------------------------------------------------------------------
// */

// Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
//     // Dashboard
//     Route::get('/dashboard', function () {
//         return view('admin.dashboard');
//     })->name('dashboard');
    
//     // Users Management
//     Route::get('/users', function () {
//         return view('admin.users');
//     })->name('users');
    
//     Route::get('/users/create', function () {
//         return view('admin.users.create');
//     })->name('users.create');
    
//     // Apartments Management
//     Route::get('/apartments', function () {
//         return view('admin.apartments');
//     })->name('apartments');
    
//     Route::get('/apartments/create', function () {
//         return view('admin.apartments.create');
//     })->name('apartments.create');
    
//     // Rentals Management
//     Route::get('/rentals', function () {
//         return view('admin.rentals');
//     })->name('rentals');
    
//     // Reviews Management
//     Route::get('/reviews', function () {
//         return view('admin.reviews');
//     })->name('reviews');
    
//     // Reports
//     Route::get('/reports', function () {
//         return view('admin.reports');
//     })->name('reports');
    
//     // Settings
//     Route::get('/settings', function () {
//         return view('admin.settings');
//     })->name('settings');
    
//     // Other admin pages
//     Route::get('/payments', function () {
//         return view('admin.payments');
//     })->name('payments');
    
//     Route::get('/inquiries', function () {
//         return view('admin.inquiries');
//     })->name('inquiries');
    
//     Route::get('/notifications', function () {
//         return view('admin.notifications');
//     })->name('notifications');
    
//     Route::get('/logs', function () {
//         return view('admin.logs');
//     })->name('logs');
    
//     Route::get('/analytics', function () {
//         return view('admin.analytics');
//     })->name('analytics');
    
//     Route::get('/support', function () {
//         return view('admin.support');
//     })->name('support');
    
//     // Admin Profile
//     Route::get('/profile', function () {
//         return view('admin.profile');
//     })->name('profile');
    
//     // Admin Logout
//     Route::post('/logout', function () {
//         Auth::guard('web')->logout();
//         request()->session()->invalidate();
//         request()->session()->regenerateToken();
//         return redirect('/');
//     })->name('logout');
// });

// /*
// |--------------------------------------------------------------------------
// | API Routes (Separate from web routes)
// |--------------------------------------------------------------------------
// */

// Route::prefix('api')->group(function () {
//     //! AUTH ROUTES
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
//     Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
//     Route::post('/update-profile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');
//     Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
//     Route::post('verify-email', [AuthController::class, 'verifyEmail'])->middleware('auth:sanctum');
//     Route::post('resend-verification', [AuthController::class, 'resendVerification'])->middleware('auth:sanctum');

//     //! USER ROUTES
//     Route::get('/users', [UserController::class, 'index'])->middleware('auth:sanctum');
//     Route::post('/users', [UserController::class, 'store'])->middleware('auth:sanctum');
//     Route::get('/users/{id}', [UserController::class, 'show'])->middleware('auth:sanctum');
//     Route::put('/users/{id}', [UserController::class, 'update'])->middleware('auth:sanctum');
//     Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('auth:sanctum');

//     //! Admin API Routes
//     Route::middleware(['auth:sanctum', 'admin'])->group(function () {
//         Route::get('/admin/users', [AdminController::class, 'listUsers']);
//         Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
//         Route::post('/admin/users/{id}/reject', [AdminController::class, 'rejectUser']);
//         Route::post('/admin/users/{id}/approve', [AdminController::class, 'approveUser']);
//         Route::get('/admin/reports', [AdminController::class, 'viewReports']);
//         Route::get('/admin/stats', [AdminController::class, 'getStats']);
//     });
// });

// /*
// |--------------------------------------------------------------------------
// | Utility Routes
// |--------------------------------------------------------------------------
// */

// // Create admin user (for testing)
// Route::get('/create-admin', function () {
//     $user = \App\Models\User::firstOrCreate(
//         ['email' => 'admin@example.com'],
//         [
//             'first_name' => 'Admin',
//             'last_name' => 'User',
//             'username' => 'admin',
//             'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
//             'role' => 'admin',
//             'status' => 'active',
//             'email_verified_at' => now(),
//         ]
//     );
    
//     return 'Admin user created/updated:<br>Email: admin@example.com<br>Password: admin123';
// });