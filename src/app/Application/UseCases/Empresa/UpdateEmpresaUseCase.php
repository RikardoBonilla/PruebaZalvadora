<?php

namespace App\Application\UseCases\Empresa;

use App\Application\DTOs\Empresa\UpdateEmpresaDTO;
use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\Repositories\EmpresaRepositoryInterface;
use App\Domain\Empresa\ValueObjects\EmpresaId;
use App\Domain\Empresa\ValueObjects\EmpresaNombre;
use App\Domain\Plan\Repositories\PlanRepositoryInterface;
use App\Domain\Plan\ValueObjects\PlanId;
use App\Domain\Shared\ValueObjects\Email;

/**
 * Caso de uso para actualizar una empresa existente.
 * Maneja la lógica de negocio para modificar información de empresas y cambios de plan.
 */
class UpdateEmpresaUseCase
{
    public function __construct(
        private EmpresaRepositoryInterface $empresaRepository,
        private PlanRepositoryInterface $planRepository
    ) {
    }

    /**
     * Ejecuta el caso de uso para actualizar una empresa.
     */
    public function execute(string $empresaId, UpdateEmpresaDTO $dto): Empresa
    {
        // Buscar la empresa
        $empresaIdVO = EmpresaId::fromString($empresaId);
        $empresa = $this->empresaRepository->findById($empresaIdVO);
        
        if (!$empresa) {
            throw new \InvalidArgumentException('La empresa no existe');
        }

        // Validar que el email no esté en uso por otra empresa
        $email = Email::fromString($dto->email);
        if ($this->empresaRepository->existsByEmailExcludingId($email, $empresaIdVO)) {
            throw new \InvalidArgumentException('Ya existe otra empresa con este email');
        }

        // Si se debe cambiar el plan, validar que existe
        if ($dto->debeCambiarPlan()) {
            $planId = PlanId::fromString($dto->planId);
            $plan = $this->planRepository->findById($planId);
            
            if (!$plan) {
                throw new \InvalidArgumentException('El plan especificado no existe');
            }

            // Cambiar el plan de la empresa
            $empresa->cambiarPlan($planId, $dto->motivoCambio ?? 'Cambio manual');
        }

        // Actualizar información básica
        $empresa->actualizar(
            EmpresaNombre::fromString($dto->nombre),
            $email
        );

        // Guardar los cambios
        $this->empresaRepository->save($empresa);

        return $empresa;
    }
}