<?php

declare(strict_types=1);

namespace App\Domain\Plan\ValueObjects;

use InvalidArgumentException;

final readonly class UserLimit
{
    public function __construct(public int $value)
    {
        if ($value < 1) {
            throw new InvalidArgumentException('User limit must be at least 1');
        }
    }

    public function isExceeded(int $currentUsers): bool
    {
        return $currentUsers > $this->value;
    }

    public function canAddUsers(int $currentUsers, int $usersToAdd): bool
    {
        return ($currentUsers + $usersToAdd) <= $this->value;
    }

    public function equals(UserLimit $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}