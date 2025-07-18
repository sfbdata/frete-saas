<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FreteRequestController;
use App\Http\Controllers\FreteiroProfileController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/fretes', [FreteRequestController::class, 'store']);
Route::post('/fretes/request', [FreteRequestController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me',     [AuthController::class, 'me']);
    Route::post('/logout',[AuthController::class, 'logout']);
    Route::post('/freteiro-profile', [FreteiroProfileController::class, 'store']);
});
