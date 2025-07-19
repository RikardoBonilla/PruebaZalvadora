<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Modelo Eloquent para la tabla historial_suscripciones.
 * Conserva el registro de todos los cambios de plan de las empresas a lo largo del tiempo.
 */
class HistorialSuscripcionModel extends Model
{
    use HasFactory;

    protected $table = 'historial_suscripciones';
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'empresa_id',
        'plan_id',
        'fecha_inicio',
        'fecha_fin',
        'motivo_cambio',
        'precio_mensual_monto',
        'precio_mensual_moneda',
    ];

    protected $casts = [
        'id' => 'string',
        'empresa_id' => 'string',
        'plan_id' => 'string',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'precio_mensual_monto' => 'decimal:2',
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
     * Relación: Un registro de historial pertenece a una empresa.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(EmpresaModel::class, 'empresa_id');
    }

    /**
     * Relación: Un registro de historial pertenece a un plan.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanModel::class, 'plan_id');
    }

    /**
     * Scope para obtener solo suscripciones activas (sin fecha de fin).
     */
    public function scopeActivas($query)
    {
        return $query->whereNull('fecha_fin');
    }

    /**
     * Scope para obtener el historial de una empresa específica.
     */
    public function scopePorEmpresa($query, string $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    /**
     * Scope para obtener suscripciones en un rango de fechas.
     */
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
    }

    /**
     * Verifica si esta suscripción está actualmente activa.
     */
    public function getEsActivaAttribute(): bool
    {
        return is_null($this->fecha_fin);
    }
}