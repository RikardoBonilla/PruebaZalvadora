<?php

declare(strict_types=1);

namespace App\Domain\Plan\Entities;

use App\Domain\Plan\ValueObjects\Features;
use App\Domain\Plan\ValueObjects\PlanId;
use App\Domain\Plan\ValueObjects\PlanName;
use App\Domain\Plan\ValueObjects\UserLimit;
use App\Domain\Shared\ValueObjects\Money;
use DateTimeImmutable;

final class Plan
{
    public function __construct(
        private PlanId $id,
        private PlanName $name,
        private Money $monthlyPrice,
        private UserLimit $userLimit,
        private Features $features,
        private DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $updatedAt = null,
    ) {
    }

    public static function create(
        PlanName $name,
        Money $monthlyPrice,
        UserLimit $userLimit,
        Features $features
    ): self {
        return new self(
            id: PlanId::generate(),
            name: $name,
            monthlyPrice: $monthlyPrice,
            userLimit: $userLimit,
            features: $features,
            createdAt: new DateTimeImmutable(),
        );
    }

    public function update(
        PlanName $name,
        Money $monthlyPrice,
        UserLimit $userLimit,
        Features $features
    ): void {
        $this->name = $name;
        $this->monthlyPrice = $monthlyPrice;
        $this->userLimit = $userLimit;
        $this->features = $features;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function canAccommodateUsers(int $userCount): bool
    {
        return !$this->userLimit->isExceeded($userCount);
    }

    public function getId(): PlanId
    {
        return $this->id;
    }

    public function getName(): PlanName
    {
        return $this->name;
    }

    public function getMonthlyPrice(): Money
    {
        return $this->monthlyPrice;
    }

    public function getUserLimit(): UserLimit
    {
        return $this->userLimit;
    }

    public function getFeatures(): Features
    {
        return $this->features;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}