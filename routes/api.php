<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//! Admin Routes -----------------------------------------------------------------
Route::prefix('admin')->group(function () {
    // تسجيل دخول الأدمن
    Route::post('/login', [\App\Http\Controllers\Admin\AuthAdminController::class, 'login']);

    // CSRF Cookie
    Route::get('/csrf-cookie', function () {
        return response()->json([
            'csrf_token' => csrf_token()
        ]);
    });
});
// 🔧 **إضافة route لإنشاء Session للـ Web**
Route::middleware('auth:sanctum')->post('/admin/create-session', function (Request $request) {
    // إنشاء Session للمستخدم
    Auth::guard('web')->login($request->user());

    return response()->json([
        'success' => true,
        'message' => 'تم إنشاء Session للـ Web'
    ]);
});

//! Admin Protected Routes -------------------------------------------------------
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Admin\AuthAdminController::class, 'profile']);

    //! User Management
    Route::get('/users', [\App\Http\Controllers\Admin\AuthAdminController::class, 'listUsers']);
    Route::get('/users/{id}', [\App\Http\Controllers\Admin\AuthAdminController::class, 'showUser']);
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\AuthAdminController::class, 'deleteUser']);
    Route::get('/reject-user/{id}', [\App\Http\Controllers\Admin\AuthAdminController::class, 'rejectUser']);
    Route::get('/approve-user/{id}', [\App\Http\Controllers\Admin\AuthAdminController::class, 'approveUser']);

    //! Stats and Reports
    Route::get('/stats', [\App\Http\Controllers\Admin\AuthAdminController::class, 'getStats']);
    Route::get('/reports', [\App\Http\Controllers\Admin\AuthAdminController::class, 'viewReports']);

    //! Apartment Management
    Route::get('/apartments', [\App\Http\Controllers\Admin\ApartmentController::class, 'index']);
    Route::get('/apartments/{id}', [\App\Http\Controllers\Admin\ApartmentController::class, 'show']);
    Route::delete('/apartments/{id}', [\App\Http\Controllers\Admin\ApartmentController::class, 'destroy']);
    Route::post('/apartments/{id}/approve', [\App\Http\Controllers\Admin\ApartmentController::class, 'approve']);
    Route::post('/apartments/{id}/reject', [\App\Http\Controllers\Admin\ApartmentController::class, 'reject']);

    //! Rental Management
    Route::get('/rentals', [\App\Http\Controllers\Admin\RentalController::class, 'index']);
    Route::get('/rentals/{id}', [\App\Http\Controllers\Admin\RentalController::class, 'show']);
    Route::delete('/rentals/{id}', [\App\Http\Controllers\Admin\RentalController::class, 'destroy']);
    Route::post('/rentals/{id}/approve', [\App\Http\Controllers\Admin\RentalController::class, 'approve']);
    Route::post('/rentals/{id}/reject', [\App\Http\Controllers\Admin\RentalController::class, 'reject']);
});

//! Regular User Auth Routes ----------------------------------------------------
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\AuthController::class, 'profile']);
    Route::post('/update-profile', [\App\Http\Controllers\AuthController::class, 'updateProfile']);
    Route::post('/change-password', [\App\Http\Controllers\AuthController::class, 'changePassword']);

    // User Apartments
    Route::get('/user/apartments', [\App\Http\Controllers\ApartmentController::class, 'index']);
    Route::post('/user/apartments', [\App\Http\Controllers\ApartmentController::class, 'store']);
    Route::get('/user/apartments/{id}', [\App\Http\Controllers\ApartmentController::class, 'show']);
    Route::put('/user/apartments/{id}', [\App\Http\Controllers\ApartmentController::class, 'update']);
    Route::delete('/user/apartments/{id}', [\App\Http\Controllers\ApartmentController::class, 'destroy']);
});