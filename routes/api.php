<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin\AuthAdminController;
use \App\Http\Controllers\Admin\ApartmentController;
use \App\Http\Controllers\Admin\RentalController;
use \App\Http\Controllers\User\RentalController as UserRentalController;
use \App\Http\Controllers\User\ApartmentController as UserApartmentController;
use \App\Http\Controllers\User\FavoriteController;
use \App\Http\Controllers\User\ReviewController as UserReviewController;
;
use \App\Http\Controllers\UserAuth\AuthController;
use Illuminate\Support\Facades\Auth;

//! Admin Routes -----------------------------------------------------------------
Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthAdminController::class, 'login']);

    Route::get('/csrf-cookie', function () {
        return response()->json([
            'csrf_token' => csrf_token()
        ]);
    });
});
Route::middleware('auth:sanctum')->post('/admin/create-session', function (Request $request) {
    Auth::guard('web')->login($request->user());
    return response()->json([
        'success' => true,
        'message' => 'Admin session created successfully.'
    ]);
});
//! Admin Protected Routes -------------------------------------------------------
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    Route::get('/profile', [AuthAdminController::class, 'profile']);

    //! User Management
    Route::get('/users', [AuthAdminController::class, 'listUsers']);
    Route::get('/users/{id}', [AuthAdminController::class, 'showUser']);
    Route::delete('/users/{id}', [AuthAdminController::class, 'deleteUser']);
    Route::get('/reject-user/{id}', [AuthAdminController::class, 'rejectUser']);
    Route::get('/approve-user/{id}', [AuthAdminController::class, 'approveUser']);
    Route::post('/users/{id}/charge-wallet', [AuthAdminController::class, 'chargeWallet']);
    //! Stats and Reports
    // TODO
    Route::get('/stats', [AuthAdminController::class, 'getStats']);
    Route::get('/reports', [AuthAdminController::class, 'viewReports']);

    //! Apartment Management
    Route::get('/apartments', [ApartmentController::class, 'index']);
    Route::get('/apartments/{id}', [ApartmentController::class, 'show']);
    Route::delete('/apartments/{id}', [ApartmentController::class, 'destroy']);
    Route::post('/apartments/{id}/approve', [ApartmentController::class, 'approve']);
    Route::post('/apartments/{id}/reject', [ApartmentController::class, 'reject']);
    //! Rental Management
    // TODO
    Route::get('/rentals', [RentalController::class, 'index']);
    Route::get('/rentals/{id}', [RentalController::class, 'show']);
    Route::delete('/rentals/{id}', [RentalController::class, 'destroy']);
    Route::post('/rentals/{id}/approve', [RentalController::class, 'approve']);
    Route::post('/rentals/{id}/reject', [RentalController::class, 'reject']);
});
//! Regular User Auth Routes ----------------------------------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    //! User Auth Routes
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    //! User Apartment Routes
    Route::get('/user/apartments', [UserApartmentController::class, 'index']);
    Route::post('/user/apartments', [UserApartmentController::class, 'store']);
    Route::get('/user/apartments/{id}', [UserApartmentController::class, 'show']);
    Route::put('/user/apartments/{id}', [UserApartmentController::class, 'update']);
    Route::delete('/user/apartments/{id}', [UserApartmentController::class, 'destroy']);
    //! User Rental Routes

    Route::get('/user/rentals', [UserRentalController::class, 'index']);
    Route::get('/user/rentals/{id}', [UserRentalController::class, 'show']);
    // TODO
    Route::get('/user/apartments/{id}/availability', [UserRentalController::class, 'checkifAvailable']);
    Route::post('/user/apartments/{id}/rent', [UserRentalController::class, 'store']);
    //! User Favorite Routes
    // TODO
    Route::get('/user/favorites', [FavoriteController::class, 'index']);
    Route::post('/user/favorites/{apartmentId}', [FavoriteController::class, 'add']);
    Route::delete('/user/favorites/{apartmentId}', [FavoriteController::class, 'remove']);
    //! User Review Routes
    // TODO
    Route::get('/user/reviews', [UserReviewController::class, 'index']);
    Route::post('/user/reviews', [UserReviewController::class, 'store']);
    Route::put('/user/reviews/{id}', [UserReviewController::class, 'update']);
    Route::delete('/user/reviews/{id}', [UserReviewController::class, 'destroy']);

});