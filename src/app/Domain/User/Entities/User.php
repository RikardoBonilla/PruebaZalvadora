<?php

declare(strict_types=1);

namespace App\Domain\User\Entities;

use App\Domain\Company\ValueObjects\CompanyId;
use App\Domain\Shared\ValueObjects\Email;
use App\Domain\User\ValueObjects\UserId;
use App\Domain\User\ValueObjects\UserName;
use App\Domain\User\ValueObjects\UserRole;
use DateTimeImmutable;

final class User
{
    public function __construct(
        private UserId $id,
        private UserName $name,
        private Email $email,
        private CompanyId $companyId,
        private UserRole $role,
        private string $hashedPassword,
        private DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $updatedAt = null,
    ) {
    }

    public static function create(
        UserName $name,
        Email $email,
        CompanyId $companyId,
        UserRole $role,
        string $hashedPassword
    ): self {
        return new self(
            id: UserId::generate(),
            name: $name,
            email: $email,
            companyId: $companyId,
            role: $role,
            hashedPassword: $hashedPassword,
            createdAt: new DateTimeImmutable(),
        );
    }

    public function updateName(UserName $name): void
    {
        $this->name = $name;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function changeRole(UserRole $role): void
    {
        $this->role = $role;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updatePassword(string $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function belongsToCompany(CompanyId $companyId): bool
    {
        return $this->companyId->equals($companyId);
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getName(): UserName
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getCompanyId(): CompanyId
    {
        return $this->companyId;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
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