<?php

declare(strict_types=1);

namespace App\Domain\Plan\Repositories;

use App\Domain\Plan\Entities\Plan;
use App\Domain\Plan\ValueObjects\PlanId;

interface PlanRepositoryInterface
{
    public function save(Plan $plan): void;

    public function findById(PlanId $id): ?Plan;

    public function findAll(): array;

    public function delete(PlanId $id): void;

    public function exists(PlanId $id): bool;
}