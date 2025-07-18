<?php

declare(strict_types=1);

namespace App\Domain\Plan\Events;

use App\Domain\Plan\Entities\Plan;
use App\Domain\Shared\Events\DomainEvent;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class PlanCreated implements DomainEvent
{
    private readonly string $eventId;
    private readonly DateTimeImmutable $occurredAt;

    public function __construct(
        private readonly Plan $plan,
        ?string $eventId = null,
        ?DateTimeImmutable $occurredAt = null,
    ) {
        $this->eventId = $eventId ?? Uuid::uuid4()->toString();
        $this->occurredAt = $occurredAt ?? new DateTimeImmutable();
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function getOccurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function getEventName(): string
    {
        return 'plan.created';
    }

    public function getPayload(): array
    {
        return [
            'plan_id' => $this->plan->getId()->value(),
            'name' => $this->plan->getName()->value,
            'monthly_price' => $this->plan->getMonthlyPrice()->toArray(),
            'user_limit' => $this->plan->getUserLimit()->value,
            'features' => $this->plan->getFeatures()->toArray(),
        ];
    }

    public function getPlan(): Plan
    {
        return $this->plan;
    }
}