<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Company\Entities\Company;
use App\Domain\Company\Repositories\CompanyRepositoryInterface;
use App\Domain\Company\ValueObjects\CompanyId;
use App\Domain\Company\ValueObjects\CompanyName;
use App\Domain\Plan\ValueObjects\PlanId;
use App\Domain\Shared\ValueObjects\Email;
use App\Infrastructure\Models\CompanyModel;
use DateTimeImmutable;

final class EloquentCompanyRepository implements CompanyRepositoryInterface
{
    public function save(Company $company): void
    {
        CompanyModel::updateOrCreate(
            ['id' => $company->getId()->value()],
            [
                'name' => $company->getName()->value,
                'email' => $company->getEmail()->value,
                'plan_id' => $company->getPlanId()->value(),
            ]
        );
    }

    public function findById(CompanyId $id): ?Company
    {
        $model = CompanyModel::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function findByEmail(Email $email): ?Company
    {
        $model = CompanyModel::where('email', $email->value)->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function exists(CompanyId $id): bool
    {
        return CompanyModel::where('id', $id->value())->exists();
    }

    public function emailExists(Email $email): bool
    {
        return CompanyModel::where('email', $email->value)->exists();
    }

    private function toDomain(CompanyModel $model): Company
    {
        return new Company(
            id: new CompanyId($model->id),
            name: new CompanyName($model->name),
            email: new Email($model->email),
            planId: new PlanId($model->plan_id),
            createdAt: new DateTimeImmutable($model->created_at->toDateTimeString()),
            updatedAt: $model->updated_at ? new DateTimeImmutable($model->updated_at->toDateTimeString()) : null,
        );
    }
}