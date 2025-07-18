<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObjects;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract readonly class Id
{
    protected UuidInterface $value;

    public function __construct(string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new InvalidArgumentException('Invalid UUID format');
        }

        $this->value = Uuid::fromString($value);
    }

    public static function generate(): static
    {
        return new static(Uuid::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value->toString();
    }

    public function equals(Id $other): bool
    {
        return $this->value()->equals($other->value());
    }

    public function __toString(): string
    {
        return $this->value();
    }
}