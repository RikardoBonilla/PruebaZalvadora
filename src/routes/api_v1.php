<?php

use App\Presentation\Controllers\Api\V1\PlanController;
use App\Presentation\Controllers\Api\V1\AuthController;
use App\Presentation\Controllers\Api\V1\EmpresaController;
use App\Presentation\Controllers\Api\V1\UsuarioEmpresaController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Rutas públicas (sin autenticación)
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::get('planes', [PlanController::class, 'index']);
    Route::get('planes/{plane}', [PlanController::class, 'show']);
    
    // Rutas públicas de empresas (solo GET)
    Route::get('empresas', [EmpresaController::class, 'index']);
    Route::get('empresas/{empresa}', [EmpresaController::class, 'show']);
    
    // Rutas públicas de usuarios de empresa (solo GET)
    Route::get('empresas/{empresa}/usuarios', [UsuarioEmpresaController::class, 'index']);
    Route::get('empresas/{empresa}/usuarios/{usuario}', [UsuarioEmpresaController::class, 'show']);
    
    // Rutas protegidas (requieren autenticación)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        
        // Rutas de planes (requieren autenticación de admin)
        Route::post('planes', [PlanController::class, 'store']);
        Route::put('planes/{plane}', [PlanController::class, 'update']);
        Route::delete('planes/{plane}', [PlanController::class, 'destroy']);
        
        // Rutas de empresas que requieren autenticación (POST, PUT, DELETE)
        Route::post('empresas', [EmpresaController::class, 'store']);
        Route::put('empresas/{empresa}', [EmpresaController::class, 'update']);
        Route::delete('empresas/{empresa}', [EmpresaController::class, 'destroy']);
        
        // Rutas de usuarios de empresa que requieren autenticación (POST, PUT, DELETE)
        Route::post('empresas/{empresa}/usuarios', [UsuarioEmpresaController::class, 'store']);
        Route::put('empresas/{empresa}/usuarios/{usuario}', [UsuarioEmpresaController::class, 'update']);
        Route::delete('empresas/{empresa}/usuarios/{usuario}', [UsuarioEmpresaController::class, 'destroy']);
    });
});