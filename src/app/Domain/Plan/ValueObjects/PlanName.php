<?php

declare(strict_types=1);

namespace App\Domain\Plan\ValueObjects;

use InvalidArgumentException;

final readonly class PlanName
{
    public function __construct(public string $value)
    {
        if (empty(trim($value))) {
            throw new InvalidArgumentException('Plan name cannot be empty');
        }

        if (strlen($value) > 100) {
            throw new InvalidArgumentException('Plan name cannot exceed 100 characters');
        }
    }

    public function equals(PlanName $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}