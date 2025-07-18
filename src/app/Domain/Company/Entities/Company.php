<?php

declare(strict_types=1);

namespace App\Domain\Company\Entities;

use App\Domain\Company\ValueObjects\CompanyId;
use App\Domain\Company\ValueObjects\CompanyName;
use App\Domain\Plan\ValueObjects\PlanId;
use App\Domain\Shared\ValueObjects\Email;
use DateTimeImmutable;

final class Company
{
    public function __construct(
        private CompanyId $id,
        private CompanyName $name,
        private Email $email,
        private PlanId $planId,
        private DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $updatedAt = null,
    ) {
    }

    public static function create(
        CompanyName $name,
        Email $email,
        PlanId $planId
    ): self {
        return new self(
            id: CompanyId::generate(),
            name: $name,
            email: $email,
            planId: $planId,
            createdAt: new DateTimeImmutable(),
        );
    }

    public function updateName(CompanyName $name): void
    {
        $this->name = $name;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function changePlan(PlanId $planId): void
    {
        $this->planId = $planId;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): CompanyId
    {
        return $this->id;
    }

    public function getName(): CompanyName
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPlanId(): PlanId
    {
        return $this->planId;
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