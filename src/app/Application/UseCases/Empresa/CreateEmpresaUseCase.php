<?php

namespace App\Application\UseCases\Empresa;

use App\Application\DTOs\Empresa\CreateEmpresaDTO;
use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\Repositories\EmpresaRepositoryInterface;
use App\Domain\Empresa\ValueObjects\EmpresaNombre;
use App\Domain\Plan\Repositories\PlanRepositoryInterface;
use App\Domain\Plan\ValueObjects\PlanId;
use App\Domain\Shared\ValueObjects\Email;

/**
 * Caso de uso para crear una nueva empresa.
 * Maneja la lógica de negocio para la creación de empresas con validaciones correspondientes.
 */
class CreateEmpresaUseCase
{
    public function __construct(
        private EmpresaRepositoryInterface $empresaRepository,
        private PlanRepositoryInterface $planRepository
    ) {
    }

    /**
     * Ejecuta el caso de uso para crear una empresa.
     */
    public function execute(CreateEmpresaDTO $dto): Empresa
    {
        // Validar que el plan existe
        $planId = PlanId::fromString($dto->planId);
        $plan = $this->planRepository->findById($planId);
        
        if (!$plan) {
            throw new \InvalidArgumentException('El plan especificado no existe');
        }

        // Validar que el email no esté en uso
        $email = Email::fromString($dto->email);
        if ($this->empresaRepository->existsByEmail($email)) {
            throw new \InvalidArgumentException('Ya existe una empresa con este email');
        }

        // Crear la entidad empresa
        $empresa = Empresa::crear(
            EmpresaNombre::fromString($dto->nombre),
            $email,
            $planId
        );

        // Guardar la empresa
        $this->empresaRepository->save($empresa);

        return $empresa;
    }
}