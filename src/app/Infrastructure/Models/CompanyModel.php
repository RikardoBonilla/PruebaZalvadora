<?php

declare(strict_types=1);

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyModel extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'plan_id',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanModel::class, 'plan_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(UserModel::class, 'company_id');
    }
}