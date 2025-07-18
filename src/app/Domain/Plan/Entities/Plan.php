<?php

declare(strict_types=1);

namespace App\Domain\Plan\Entities;

use App\Domain\Plan\ValueObjects\Features;
use App\Domain\Plan\ValueObjects\PlanId;
use App\Domain\Plan\ValueObjects\PlanName;
use App\Domain\Plan\ValueObjects\UserLimit;
use App\Domain\Shared\ValueObjects\Money;
use DateTimeImmutable;

/**
 * Entidad Plan de Suscripción
 * 
 * Representa un plan de suscripción en el dominio de negocio.
 * Encapsula toda la lógica relacionada con los planes, incluyendo:
 * - Gestión de precios mensuales
 * - Límites de usuarios
 * - Características incluidas
 * - Validaciones de capacidad
 * 
 * Esta entidad es el agregado raíz del contexto Plan.
 */
final class Plan
{
    /**
     * Constructor de la entidad Plan
     * 
     * @param PlanId $id Identificador único del plan
     * @param PlanName $name Nombre del plan
     * @param Money $monthlyPrice Precio mensual del plan
     * @param UserLimit $userLimit Límite de usuarios permitidos
     * @param Features $features Características incluidas en el plan
     * @param DateTimeImmutable $createdAt Fecha de creación
     * @param DateTimeImmutable|null $updatedAt Fecha de última actualización
     */
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

    /**
     * Factory method para crear un nuevo plan
     * 
     * @param PlanName $name Nombre del plan
     * @param Money $monthlyPrice Precio mensual
     * @param UserLimit $userLimit Límite de usuarios
     * @param Features $features Características del plan
     * @return self Nueva instancia de Plan
     */
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

    /**
     * Actualiza los datos del plan
     * 
     * @param PlanName $name Nuevo nombre del plan
     * @param Money $monthlyPrice Nuevo precio mensual
     * @param UserLimit $userLimit Nuevo límite de usuarios
     * @param Features $features Nuevas características
     */
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

    /**
     * Verifica si el plan puede acomodar la cantidad de usuarios especificada
     * 
     * @param int $userCount Cantidad de usuarios a verificar
     * @return bool true si el plan puede acomodar esa cantidad de usuarios
     */
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