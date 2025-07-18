<?php

declare(strict_types=1);

namespace App\Application\UseCases\Plan;

use App\Domain\Plan\Repositories\PlanRepositoryInterface;
use App\Domain\Plan\ValueObjects\PlanId;
use InvalidArgumentException;

final readonly class DeletePlanUseCase
{
    public function __construct(
        private PlanRepositoryInterface $planRepository,
    ) {
    }

    public function execute(string $planId): void
    {
        $id = new PlanId($planId);

        if (!$this->planRepository->exists($id)) {
            throw new InvalidArgumentException('Plan not found');
        }

        $this->planRepository->delete($id);
    }
}