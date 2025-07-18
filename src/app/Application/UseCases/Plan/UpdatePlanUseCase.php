<?php

declare(strict_types=1);

namespace App\Application\UseCases\Plan;

use App\Application\DTOs\Plan\UpdatePlanDTO;
use App\Domain\Plan\Entities\Plan;
use App\Domain\Plan\Repositories\PlanRepositoryInterface;
use App\Domain\Plan\ValueObjects\Features;
use App\Domain\Plan\ValueObjects\PlanId;
use App\Domain\Plan\ValueObjects\PlanName;
use App\Domain\Plan\ValueObjects\UserLimit;
use App\Domain\Shared\ValueObjects\Money;
use InvalidArgumentException;

final readonly class UpdatePlanUseCase
{
    public function __construct(
        private PlanRepositoryInterface $planRepository,
    ) {
    }

    public function execute(UpdatePlanDTO $dto): Plan
    {
        $planId = new PlanId($dto->id);
        $plan = $this->planRepository->findById($planId);

        if (!$plan) {
            throw new InvalidArgumentException('Plan not found');
        }

        $plan->update(
            name: new PlanName($dto->name),
            monthlyPrice: new Money($dto->monthlyPrice, $dto->currency),
            userLimit: new UserLimit($dto->userLimit),
            features: new Features($dto->features),
        );

        $this->planRepository->save($plan);

        return $plan;
    }
}