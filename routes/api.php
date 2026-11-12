<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetorApiController;
use App\Http\Controllers\Api\FabricaController;
use App\Http\Controllers\Api\AuthController;


Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('setores', SetorApiController::class)
    ->names('api.setores');
    Route::apiResource('fabrica', FabricaController::class);
});



