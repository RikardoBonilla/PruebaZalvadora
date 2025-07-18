<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Plan\Entities\Plan;
use App\Domain\Plan\Repositories\PlanRepositoryInterface;
use App\Domain\Plan\ValueObjects\Features;
use App\Domain\Plan\ValueObjects\PlanId;
use App\Domain\Plan\ValueObjects\PlanName;
use App\Domain\Plan\ValueObjects\UserLimit;
use App\Domain\Shared\ValueObjects\Money;
use App\Infrastructure\Models\PlanModel;
use DateTimeImmutable;

/**
 * Repositorio Eloquent para Planes
 * 
 * Implementación concreta del repositorio de planes usando Eloquent ORM.
 * Actúa como adaptador entre la capa de dominio y la persistencia de datos.
 * 
 * Responsabilidades:
 * - Conversión entre entidades de dominio y modelos Eloquent
 * - Operaciones CRUD sobre la tabla de planes
 * - Mapeo de objetos de valor a campos de base de datos
 */
final class EloquentPlanRepository implements PlanRepositoryInterface
{
    /**
     * Guarda o actualiza un plan en la base de datos
     * 
     * @param Plan $plan Entidad de dominio a persistir
     */
    public function save(Plan $plan): void
    {
        PlanModel::updateOrCreate(
            ['id' => $plan->getId()->value()],
            [
                'name' => $plan->getName()->value,
                'monthly_price_amount' => $plan->getMonthlyPrice()->amount,
                'monthly_price_currency' => $plan->getMonthlyPrice()->currency,
                'user_limit' => $plan->getUserLimit()->value,
                'features' => $plan->getFeatures()->toArray(),
            ]
        );
    }

    /**
     * Busca un plan por su identificador
     * 
     * @param PlanId $id Identificador del plan
     * @return Plan|null La entidad de dominio o null si no existe
     */
    public function findById(PlanId $id): ?Plan
    {
        $model = PlanModel::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    /**
     * Obtiene todos los planes de la base de datos
     * 
     * @return Plan[] Array de entidades de dominio
     */
    public function findAll(): array
    {
        return PlanModel::all()
            ->map(fn(PlanModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function delete(PlanId $id): void
    {
        PlanModel::destroy($id->value());
    }

    public function exists(PlanId $id): bool
    {
        return PlanModel::where('id', $id->value())->exists();
    }

    /**
     * Convierte un modelo Eloquent a entidad de dominio
     * 
     * @param PlanModel $model Modelo Eloquent desde la base de datos
     * @return Plan Entidad de dominio correspondiente
     */
    private function toDomain(PlanModel $model): Plan
    {
        return new Plan(
            id: new PlanId($model->id),
            name: new PlanName($model->name),
            monthlyPrice: new Money($model->monthly_price_amount, $model->monthly_price_currency),
            userLimit: new UserLimit($model->user_limit),
            features: new Features($model->features ?? []),
            createdAt: new DateTimeImmutable($model->created_at->toDateTimeString()),
            updatedAt: $model->updated_at ? new DateTimeImmutable($model->updated_at->toDateTimeString()) : null,
        );
    }
}