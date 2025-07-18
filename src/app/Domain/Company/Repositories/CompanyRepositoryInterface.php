<?php

declare(strict_types=1);

namespace App\Domain\Company\Repositories;

use App\Domain\Company\Entities\Company;
use App\Domain\Company\ValueObjects\CompanyId;
use App\Domain\Shared\ValueObjects\Email;

interface CompanyRepositoryInterface
{
    public function save(Company $company): void;

    public function findById(CompanyId $id): ?Company;

    public function findByEmail(Email $email): ?Company;

    public function exists(CompanyId $id): bool;

    public function emailExists(Email $email): bool;
}