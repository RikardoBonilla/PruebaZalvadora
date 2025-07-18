<?php

declare(strict_types=1);

namespace App\Domain\Shared\Events;

use DateTimeImmutable;

interface DomainEvent
{
    public function getEventId(): string;

    public function getOccurredAt(): DateTimeImmutable;

    public function getEventName(): string;

    public function getPayload(): array;
}