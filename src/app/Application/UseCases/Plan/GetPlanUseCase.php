<?php

declare(strict_types=1);

namespace App\Application\UseCases\Plan;

use App\Domain\Plan\Entities\Plan;
use App\Domain\Plan\Repositories\PlanRepositoryInterface;
use App\Domain\Plan\ValueObjects\PlanId;
use InvalidArgumentException;

final readonly class GetPlanUseCase
{
    public function __construct(
        private PlanRepositoryInterface $planRepository,
    ) {
    }

    public function execute(string $planId): Plan
    {
        $id = new PlanId($planId);
        $plan = $this->planRepository->findById($id);

        if (!$plan) {
            throw new InvalidArgumentException('Plan not found');
        }

        return $plan;
    }
}