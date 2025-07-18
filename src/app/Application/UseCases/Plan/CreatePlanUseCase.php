<?php

declare(strict_types=1);

namespace App\Application\UseCases\Plan;

use App\Application\DTOs\Plan\CreatePlanDTO;
use App\Domain\Plan\Entities\Plan;
use App\Domain\Plan\Events\PlanCreated;
use App\Domain\Plan\Repositories\PlanRepositoryInterface;
use App\Domain\Plan\ValueObjects\Features;
use App\Domain\Plan\ValueObjects\PlanName;
use App\Domain\Plan\ValueObjects\UserLimit;
use App\Domain\Shared\ValueObjects\Money;
use Illuminate\Contracts\Events\Dispatcher;

final readonly class CreatePlanUseCase
{
    public function __construct(
        private PlanRepositoryInterface $planRepository,
        private Dispatcher $eventDispatcher,
    ) {
    }

    public function execute(CreatePlanDTO $dto): Plan
    {
        $plan = Plan::create(
            name: new PlanName($dto->name),
            monthlyPrice: new Money($dto->monthlyPrice, $dto->currency),
            userLimit: new UserLimit($dto->userLimit),
            features: new Features($dto->features),
        );

        $this->planRepository->save($plan);

        $this->eventDispatcher->dispatch(new PlanCreated($plan));

        return $plan;
    }
}