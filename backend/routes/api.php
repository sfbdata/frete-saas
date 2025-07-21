<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FreteRequestController;
use App\Http\Controllers\FreteiroProfileController;
use App\Http\Controllers\ContatoController;

// Rotas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::get('/freteiros', [FreteiroProfileController::class, 'index']);
Route::get('/freteiros/{id}', [FreteiroProfileController::class, 'show']);

Route::post('/fretes', [FreteRequestController::class, 'store']);
Route::post('/fretes/request', [FreteRequestController::class, 'store']); // redundante, mas mantida

Route::post('/fretes/{frete}/contato/{freteiro}', [ContatoController::class, 'enviarContato']);

// Rotas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me',     [AuthController::class, 'me']);
    Route::post('/logout',[AuthController::class, 'logout']);

    // Criar e atualizar perfil de freteiro
    Route::post('/freteiro-profile', [FreteiroProfileController::class, 'store']);
    Route::put('/freteiros/{id}',    [FreteiroProfileController::class, 'update']);

    // Painel do freteiro
    Route::get('/freteiro-profile/dashboard', [FreteiroProfileController::class, 'dashboard']);
});
