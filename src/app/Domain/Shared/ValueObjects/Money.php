<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObjects;

use InvalidArgumentException;

final readonly class Money
{
    public function __construct(
        public int $amount,
        public string $currency = 'USD'
    ) {
        if ($amount < 0) {
            throw new InvalidArgumentException('Amount cannot be negative');
        }

        if (empty($currency)) {
            throw new InvalidArgumentException('Currency cannot be empty');
        }
    }

    public function equals(Money $other): bool
    {
        return $this->amount === $other->amount && $this->currency === $other->currency;
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
        ];
    }

    public function __toString(): string
    {
        return sprintf('%d %s', $this->amount, $this->currency);
    }
}