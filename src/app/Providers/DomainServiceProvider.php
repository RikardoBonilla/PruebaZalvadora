<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Company\Repositories\CompanyRepositoryInterface;
use App\Domain\Plan\Repositories\PlanRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentPlanRepository;
use App\Infrastructure\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PlanRepositoryInterface::class, EloquentPlanRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, EloquentCompanyRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
