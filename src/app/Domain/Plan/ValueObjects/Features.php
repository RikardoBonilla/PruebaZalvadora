<?php

declare(strict_types=1);

namespace App\Domain\Plan\ValueObjects;

use InvalidArgumentException;

final readonly class Features
{
    public function __construct(public array $value)
    {
        foreach ($value as $feature) {
            if (!is_string($feature) || empty(trim($feature))) {
                throw new InvalidArgumentException('All features must be non-empty strings');
            }
        }
    }

    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->value, true);
    }

    public function count(): int
    {
        return count($this->value);
    }

    public function equals(Features $other): bool
    {
        return $this->value === $other->value;
    }

    public function toArray(): array
    {
        return $this->value;
    }
}