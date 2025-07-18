<?php

declare(strict_types=1);

namespace App\Application\UseCases\Plan;

use App\Application\DTOs\Plan\CreatePlanDTO;
use App\Domain\Plan\Entities\Plan;
use App\Domain\Plan\Events\PlanCreated;
use App\Domain\Plan\Repositories\PlanRepositoryInterface;
use App\Domain\Plan\ValueObjects\Features;
use App\Domain\Plan\ValueObjects\PlanName;
use App\Domain\Plan\ValueObjects\UserLimit;
use App\Domain\Shared\ValueObjects\Money;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Caso de Uso: Crear Plan
 * 
 * Orquesta el proceso de creación de un nuevo plan de suscripción.
 * Implementa la lógica de aplicación para:
 * - Crear la entidad Plan con sus objetos de valor
 * - Persistir el plan en el repositorio
 * - Disparar eventos de dominio correspondientes
 * 
 * Sigue el patrón Command con separación clara de responsabilidades.
 */
final readonly class CreatePlanUseCase
{
    /**
     * Constructor del caso de uso
     * 
     * @param PlanRepositoryInterface $planRepository Repositorio para persistir planes
     * @param Dispatcher $eventDispatcher Despachador de eventos de dominio
     */
    public function __construct(
        private PlanRepositoryInterface $planRepository,
        private Dispatcher $eventDispatcher,
    ) {
    }

    /**
     * Ejecuta el caso de uso de creación de plan
     * 
     * @param CreatePlanDTO $dto Datos transferidos desde la capa de presentación
     * @return Plan La entidad Plan creada y persistida
     */
    public function execute(CreatePlanDTO $dto): Plan
    {
        $plan = Plan::create(
            name: new PlanName($dto->name),
            monthlyPrice: new Money($dto->monthlyPrice, $dto->currency),
            userLimit: new UserLimit($dto->userLimit),
            features: new Features($dto->features),
        );

        $this->planRepository->save($plan);

        $this->eventDispatcher->dispatch(new PlanCreated($plan));

        return $plan;
    }
}