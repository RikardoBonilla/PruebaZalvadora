<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Modelo Eloquent para la tabla empresas.
 * Representa una empresa tenant en el sistema con su plan activo y relación con usuarios.
 */
class EmpresaModel extends Model
{
    use HasFactory;

    protected $table = 'empresas';
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nombre',
        'email',
        'plan_id',
        'fecha_suscripcion',
    ];

    protected $casts = [
        'id' => 'string',
        'plan_id' => 'string',
        'fecha_suscripcion' => 'datetime',
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
     * Relación: Una empresa pertenece a un plan.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanModel::class, 'plan_id');
    }

    /**
     * Relación: Una empresa puede tener muchos usuarios.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(UsuarioEmpresaModel::class, 'empresa_id');
    }

    /**
     * Relación: Una empresa puede tener muchos registros en el historial de suscripciones.
     */
    public function historialSuscripciones(): HasMany
    {
        return $this->hasMany(HistorialSuscripcionModel::class, 'empresa_id');
    }

    /**
     * Scope para obtener solo empresas activas (que tienen usuarios activos).
     */
    public function scopeActivas($query)
    {
        return $query->whereHas('usuarios', function ($q) {
            $q->where('activo', true);
        });
    }

    /**
     * Obtiene el número de usuarios activos de la empresa.
     */
    public function getUsuariosActivosCountAttribute(): int
    {
        return $this->usuarios()->where('activo', true)->count();
    }
}