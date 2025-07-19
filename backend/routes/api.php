<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FreteRequestController;
use App\Http\Controllers\FreteiroProfileController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/fretes', [FreteRequestController::class, 'store']);
Route::post('/fretes/request', [FreteRequestController::class, 'store']);
Route::get('/freteiros', [FreteiroProfileController::class, 'index']);
Route::get('/freteiros/{id}', [FreteiroProfileController::class, 'show']);
Route::post('/fretes/{frete}/contato/{freteiro}', [\App\Http\Controllers\ContatoController::class, 'enviarContato']);




Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me',     [AuthController::class, 'me']);
    Route::post('/logout',[AuthController::class, 'logout']);
    Route::post('/freteiro-profile', [FreteiroProfileController::class, 'store']);
});
