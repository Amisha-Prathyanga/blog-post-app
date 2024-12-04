<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


use App\Http\Controllers\Api\ApiPostController;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']); // Register user
Route::post('/login', [AuthController::class, 'login']); // Login user
Route::post('/logout', [AuthController::class, 'logout']); // Logout user

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/posts', [ApiPostController::class, 'index']); 
    Route::post('/posts', [ApiPostController::class, 'store']); 
    Route::get('/posts/{post}', [ApiPostController::class, 'show']); 
    Route::put('/posts/{post}', [ApiPostController::class, 'update']); 
    Route::delete('/posts/{post}', [ApiPostController::class, 'destroy']); 
});