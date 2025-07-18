<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Company\ValueObjects\CompanyId;
use App\Domain\Shared\ValueObjects\Email;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\UserId;
use App\Domain\User\ValueObjects\UserName;
use App\Domain\User\ValueObjects\UserRole;
use App\Infrastructure\Models\UserModel;
use DateTimeImmutable;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function save(User $user): void
    {
        UserModel::updateOrCreate(
            ['id' => $user->getId()->value()],
            [
                'name' => $user->getName()->value,
                'email' => $user->getEmail()->value,
                'company_id' => $user->getCompanyId()->value(),
                'role' => $user->getRole()->value,
                'password' => $user->getHashedPassword(),
            ]
        );
    }

    public function findById(UserId $id): ?User
    {
        $model = UserModel::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function findByEmail(Email $email): ?User
    {
        $model = UserModel::where('email', $email->value)->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findByCompany(CompanyId $companyId): array
    {
        return UserModel::where('company_id', $companyId->value())
            ->get()
            ->map(fn(UserModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function countByCompany(CompanyId $companyId): int
    {
        return UserModel::where('company_id', $companyId->value())->count();
    }

    public function delete(UserId $id): void
    {
        UserModel::destroy($id->value());
    }

    public function exists(UserId $id): bool
    {
        return UserModel::where('id', $id->value())->exists();
    }

    public function emailExists(Email $email): bool
    {
        return UserModel::where('email', $email->value)->exists();
    }

    private function toDomain(UserModel $model): User
    {
        return new User(
            id: new UserId($model->id),
            name: new UserName($model->name),
            email: new Email($model->email),
            companyId: new CompanyId($model->company_id),
            role: UserRole::fromString($model->role),
            hashedPassword: $model->password,
            createdAt: new DateTimeImmutable($model->created_at->toDateTimeString()),
            updatedAt: $model->updated_at ? new DateTimeImmutable($model->updated_at->toDateTimeString()) : null,
        );
    }
}