<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     * Crea la tabla usuarios_empresa para gestionar usuarios internos de cada empresa tenant.
     */
    public function up(): void
    {
        Schema::create('usuarios_empresa', function (Blueprint $table) {
            $table->uuid('id')->primary(); // ID único del usuario
            $table->string('nombre'); // Nombre del usuario
            $table->string('email')->unique(); // Email único del usuario
            $table->timestamp('email_verified_at')->nullable(); // Verificación de email
            $table->string('password'); // Contraseña encriptada
            $table->uuid('empresa_id'); // ID de la empresa a la que pertenece
            $table->enum('rol', ['admin', 'usuario'])->default('usuario'); // Rol del usuario en la empresa
            $table->boolean('activo')->default(true); // Estado del usuario
            $table->rememberToken(); // Token para "remember me"
            $table->timestamps(); // created_at y updated_at
            
            // Relación con la tabla empresas
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            
            // Índices para optimizar consultas
            $table->index('empresa_id');
            $table->index('email');
            $table->index(['empresa_id', 'activo']); // Para contar usuarios activos por empresa
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_empresa');
    }
};
