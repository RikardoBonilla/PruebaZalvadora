<?php

declare(strict_types=1);

namespace App\Domain\User\Repositories;

use App\Domain\Company\ValueObjects\CompanyId;
use App\Domain\Shared\ValueObjects\Email;
use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\UserId;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findById(UserId $id): ?User;

    public function findByEmail(Email $email): ?User;

    public function findByCompany(CompanyId $companyId): array;

    public function countByCompany(CompanyId $companyId): int;

    public function delete(UserId $id): void;

    public function exists(UserId $id): bool;

    public function emailExists(Email $email): bool;
}