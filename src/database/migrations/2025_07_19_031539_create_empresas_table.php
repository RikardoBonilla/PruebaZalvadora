<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     * Crea la tabla empresas con campos para almacenar información de cada empresa tenant.
     */
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->uuid('id')->primary(); // ID único para cada empresa
            $table->string('nombre'); // Nombre de la empresa
            $table->string('email')->unique(); // Email único de la empresa
            $table->uuid('plan_id'); // Plan activo actual de la empresa
            $table->timestamp('fecha_suscripcion'); // Fecha de suscripción al plan actual
            $table->timestamps(); // created_at y updated_at
            
            // Relación con la tabla planes
            $table->foreign('plan_id')->references('id')->on('planes')->onDelete('restrict');
            
            // Índices para optimizar consultas
            $table->index('plan_id');
            $table->index('email');
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
