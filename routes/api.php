<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrincipalTenantController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TenantController;
use L5Swagger\L5Swagger;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::put('profile', [AuthController::class, 'updateProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('users', [AuthController::class, 'listUsers']);
    Route::get('users/{id}', [AuthController::class, 'viewUser']);
    Route::put('users/{id}/role', [AuthController::class, 'updateUserRole']);
    Route::post('password/change', [AuthController::class, 'changePassword']);
});

Route::middleware('auth:sanctum', 'verified')->group(function () {
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])->name('verification.send');
});

Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);


Route::middleware(['auth:sanctum', 'role:principal_tenant'])->group(function () {
    Route::post('tenants', [PrincipalTenantController::class, 'createTenant']);
    Route::get('tenants', [PrincipalTenantController::class, 'viewTenants']);
});


Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('admin/users', [AdminController::class, 'viewAllUsers']);
    Route::put('admin/users/{id}/role', [AdminController::class, 'updateUserRole']);
    Route::put('admin/users/{id}', [AdminController::class, 'editUser']);
    Route::delete('admin/users/{id}', [AdminController::class, 'deactivateUser']);
});


Route::middleware(['auth:sanctum', 'role:tenant'])->group(function () {
    Route::put('profile', [TenantController::class, 'updateProfile']);
    Route::post('password/change', [TenantController::class, 'changePassword']);
});
