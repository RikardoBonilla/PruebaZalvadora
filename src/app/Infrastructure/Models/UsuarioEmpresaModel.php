<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

/**
 * Modelo Eloquent para la tabla usuarios_empresa.
 * Representa usuarios internos de cada empresa tenant con validación de límites según el plan.
 */
class UsuarioEmpresaModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios_empresa';
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nombre',
        'email',
        'password',
        'empresa_id',
        'rol',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id' => 'string',
        'empresa_id' => 'string',
        'email_verified_at' => 'datetime',
        'activo' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Genera automáticamente un UUID cuando se crea un nuevo registro.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

    /**
     * Relación: Un usuario pertenece a una empresa.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(EmpresaModel::class, 'empresa_id');
    }

    /**
     * Scope para obtener solo usuarios activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para obtener usuarios de una empresa específica.
     */
    public function scopePorEmpresa($query, string $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    /**
     * Scope para obtener usuarios con rol específico.
     */
    public function scopePorRol($query, string $rol)
    {
        return $query->where('rol', $rol);
    }

    /**
     * Scope para obtener administradores.
     */
    public function scopeAdministradores($query)
    {
        return $query->where('rol', 'admin');
    }

    /**
     * Verifica si el usuario es administrador.
     */
    public function getEsAdminAttribute(): bool
    {
        return $this->rol === 'admin';
    }

    /**
     * Verifica si el usuario está activo.
     */
    public function getEstaActivoAttribute(): bool
    {
        return $this->activo;
    }

    /**
     * Obtiene el plan actual de la empresa del usuario.
     */
    public function getPlanEmpresaAttribute()
    {
        return $this->empresa?->plan;
    }
}