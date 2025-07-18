<?php

declare(strict_types=1);

namespace App\Application\UseCases\Plan;

use App\Domain\Plan\Repositories\PlanRepositoryInterface;

final readonly class ListPlansUseCase
{
    public function __construct(
        private PlanRepositoryInterface $planRepository,
    ) {
    }

    public function execute(): array
    {
        return $this->planRepository->findAll();
    }
}