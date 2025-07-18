<?php

declare(strict_types=1);

namespace App\Domain\Company\ValueObjects;

use InvalidArgumentException;

final readonly class CompanyName
{
    public function __construct(public string $value)
    {
        if (empty(trim($value))) {
            throw new InvalidArgumentException('Company name cannot be empty');
        }

        if (strlen($value) > 255) {
            throw new InvalidArgumentException('Company name cannot exceed 255 characters');
        }
    }

    public function equals(CompanyName $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}