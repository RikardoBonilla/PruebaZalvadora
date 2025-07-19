<?php

use App\Presentation\Controllers\Api\V1\PlanController;
use App\Presentation\Controllers\Api\V1\AuthController;
use App\Presentation\Controllers\Api\V1\EmpresaController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Rutas públicas (sin autenticación)
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::get('planes', [PlanController::class, 'index']);
    Route::get('planes/{plane}', [PlanController::class, 'show']);
    
    // Rutas protegidas (requieren autenticación)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        
        // Rutas de planes (requieren autenticación de admin)
        Route::post('planes', [PlanController::class, 'store']);
        Route::put('planes/{plane}', [PlanController::class, 'update']);
        Route::delete('planes/{plane}', [PlanController::class, 'destroy']);
        
        // Rutas de empresas
        Route::apiResource('empresas', EmpresaController::class);
    });
});