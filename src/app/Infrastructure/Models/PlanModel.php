<?php

declare(strict_types=1);

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanModel extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'monthly_price_amount',
        'monthly_price_currency',
        'user_limit',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
        'monthly_price_amount' => 'integer',
        'user_limit' => 'integer',
    ];

    public function companies(): HasMany
    {
        return $this->hasMany(CompanyModel::class, 'plan_id');
    }
}