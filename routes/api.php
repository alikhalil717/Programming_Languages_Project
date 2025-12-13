<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//! AUTH ROUTES--------------------------------------------------------------------------------------------------
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/profile', [\App\Http\Controllers\AuthController::class, 'profile'])->middleware('auth:sanctum');
Route::post('/update-profile', [\App\Http\Controllers\AuthController::class, 'updateProfile'])->middleware('auth:sanctum');
Route::post('/change-password', [\App\Http\Controllers\AuthController::class, 'changePassword'])->middleware('auth:sanctum');


//TODO
Route::post('verify-email', [\App\Http\Controllers\AuthController::class, 'verifyEmail'])->middleware('auth:sanctum');
Route::post('resend-verification', [\App\Http\Controllers\AuthController::class, 'resendVerification'])->middleware('auth:sanctum');


//! User Routes-------------------------------------------------------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {

Route::get('/user/apartments', [\App\Http\Controllers\UserApartmentController::class, 'index']);
Route::post('/user/apartments', [\App\Http\Controllers\UserApartmentController::class, 'store']);
Route::get('/user/apartments/{id}', [\App\Http\Controllers\UserApartmentController::class, 'show']);
Route::put('/user/apartments/{id}', [\App\Http\Controllers\UserApartmentController::class, 'update']);
Route::delete('/user/apartments/{id}', [\App\Http\Controllers\UserApartmentController::class, 'destroy']);



});

//! Admin Routes-------------------------------------------------------------------------------------------------

Route::middleware(['auth:sanctum', 'admin'])->group(function () {

        //! User Management-------------
    Route::get('/admin/users', [\App\Http\Controllers\AdminController::class, 'listUsers']);
    Route::get('/admin/users/{id}', [\App\Http\Controllers\AdminController::class, 'showUser']);
    Route::delete('/admin/users/{id}', [\App\Http\Controllers\AdminController::class, 'deleteUser']);
        //TODO
    Route::get('reject-user/{id}', [\App\Http\Controllers\AdminController::class, 'rejectUser']);
    Route::get('approve-user/{id}', [\App\Http\Controllers\AdminController::class, 'approveUser']);

       //! Stats and Reports------------
       //TODO    
    Route::get('/admin/reports', [\App\Http\Controllers\AdminController::class, 'viewReports']);
    Route::get('/admin/stats', [\App\Http\Controllers\AdminController::class, 'getStats']);
        
       //! appartment management--------
       //TODO
    Route::get('/admin/apartments', [\App\Http\Controllers\AdminApartmentController::class, 'index']);
    Route::get('/admin/apartments/{id}', [\App\Http\Controllers\AdminApartmentController::class, 'show']);
    Route::delete('/admin/apartments/{id}', [\App\Http\Controllers\AdminApartmentController::class, 'destroy']);
    Route::post('/admin/apartments/{id}/approve', [\App\Http\Controllers\AdminApartmentController::class, 'approve']);
    Route::post('/admin/apartments/{id}/reject', [\App\Http\Controllers\AdminApartmentController::class, 'reject']);

       //!Rental management------------
       //TODO
    Route::get('/admin/rentals', [\App\Http\Controllers\AdminRentalController::class, 'index']);
    Route::get('/admin/rentals/{id}', [\App\Http\Controllers\AdminRentalController::class, 'show']);
    Route::delete('/admin/rentals/{id}', [\App\Http\Controllers\AdminRentalController::class, 'destroy']);
    Route::post('/admin/rentals/{id}/approve', [\App\Http\Controllers\AdminRentalController::class, 'approve']);
    Route::post('/admin/rentals/{id}/reject', [\App\Http\Controllers\AdminRentalController::class, 'reject']);

});
