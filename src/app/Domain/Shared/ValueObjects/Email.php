<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObjects;

use InvalidArgumentException;

final readonly class Email
{
    public function __construct(public string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }

    public function domain(): string
    {
        return substr($this->value, strpos($this->value, '@') + 1);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}