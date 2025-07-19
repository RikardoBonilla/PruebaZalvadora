<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     * Crea la tabla historial_suscripciones para conservar el registro de todos los cambios de plan de las empresas.
     */
    public function up(): void
    {
        Schema::create('historial_suscripciones', function (Blueprint $table) {
            $table->uuid('id')->primary(); // ID único del registro histórico
            $table->uuid('empresa_id'); // ID de la empresa
            $table->uuid('plan_id'); // ID del plan en ese momento
            $table->timestamp('fecha_inicio'); // Fecha de inicio de la suscripción
            $table->timestamp('fecha_fin')->nullable(); // Fecha de fin (null si es el plan actual)
            $table->string('motivo_cambio')->nullable(); // Razón del cambio de plan
            $table->decimal('precio_mensual_monto', 10, 2); // Precio que se pagaba en ese momento
            $table->string('precio_mensual_moneda', 3); // Moneda del precio
            $table->timestamps(); // created_at y updated_at
            
            // Relaciones foráneas
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('planes')->onDelete('restrict');
            
            // Índices para optimizar consultas
            $table->index('empresa_id');
            $table->index('plan_id');
            $table->index('fecha_inicio');
            $table->index(['empresa_id', 'fecha_fin']); // Para encontrar plan actual rápidamente
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_suscripciones');
    }
};
