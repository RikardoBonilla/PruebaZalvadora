<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObjects;

use InvalidArgumentException;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            'admin' => self::ADMIN,
            'user' => self::USER,
            default => throw new InvalidArgumentException("Invalid user role: $value"),
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function isUser(): bool
    {
        return $this === self::USER;
    }
}