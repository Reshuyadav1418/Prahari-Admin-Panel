<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AdminController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('getConnectionId', [AuthController::class, 'getConnectionId']);
Route::post('requestOtp', [AuthController::class, 'requestOtp']);
Route::post('verifyOtp', [AuthController::class, 'verifyOtp']);

//prahari app Apis for user management and case management and Dashboard management
Route::middleware('auth:sanctum')->group(function () {
    Route::post('usersList', [UserController::class, 'index']);
    Route::post('userCreate', [UserController::class, 'store']);
    Route::post('userSingle/{id}', [UserController::class, 'show']);
    Route::post('userUpdate/{id}', [UserController::class, 'update']);
    Route::post('userDelete/{id}', [UserController::class, 'destroy']);
    Route::post('cases/add', [CaseController::class, 'store']);
    // Admin panel: Prahari stats for dashboard
    Route::get('admin/praharis/{id}/stats', [AdminController::class, 'prahariStats']);
});
