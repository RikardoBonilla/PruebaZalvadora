<?php

declare(strict_types=1);

namespace App\Application\DTOs\Plan;

final readonly class UpdatePlanDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public int $monthlyPrice,
        public string $currency,
        public int $userLimit,
        public array $features,
    ) {
    }
}