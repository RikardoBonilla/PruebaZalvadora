<?php

declare(strict_types=1);

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Eloquent para la tabla de Planes
 * 
 * Representa la estructura de datos de planes en la base de datos.
 * Actúa como capa de persistencia para las entidades de dominio Plan.
 * 
 * Configuración:
 * - Usa UUIDs como claves primarias
 * - Tabla: 'planes' (actualizada al español)
 * - Campos JSON para características (features)
 * - Relaciones con empresas (companies)
 */
class PlanModel extends Model
{
    use HasFactory;

    /** @var string Nombre de la tabla en la base de datos */
    protected $table = 'planes';

    /** @var string Tipo de clave primaria (UUID como string) */
    protected $keyType = 'string';

    /** @var bool Indica que las claves primarias no son auto-incrementales */
    public $incrementing = false;

    /** @var array Campos que pueden ser asignados masivamente */
    protected $fillable = [
        'id',
        'name',
        'monthly_price_amount',
        'monthly_price_currency',
        'user_limit',
        'features',
    ];

    /** @var array Conversiones automáticas de tipos de datos */
    protected $casts = [
        'features' => 'array',
        'monthly_price_amount' => 'integer',
        'user_limit' => 'integer',
    ];

    /**
     * Relación uno a muchos con empresas
     * 
     * @return HasMany Empresas que tienen este plan
     */
    public function companies(): HasMany
    {
        return $this->hasMany(CompanyModel::class, 'plan_id');
    }
}