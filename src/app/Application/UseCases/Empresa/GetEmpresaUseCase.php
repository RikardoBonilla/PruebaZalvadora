<?php

namespace App\Application\UseCases\Empresa;

use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\Repositories\EmpresaRepositoryInterface;
use App\Domain\Empresa\ValueObjects\EmpresaId;

/**
 * Caso de uso para obtener una empresa por su ID.
 * Maneja la lógica de negocio para recuperar información de una empresa específica.
 */
class GetEmpresaUseCase
{
    public function __construct(
        private EmpresaRepositoryInterface $empresaRepository
    ) {
    }

    /**
     * Ejecuta el caso de uso para obtener una empresa.
     */
    public function execute(string $empresaId): Empresa
    {
        $empresa = $this->empresaRepository->findById(
            EmpresaId::fromString($empresaId)
        );

        if (!$empresa) {
            throw new \InvalidArgumentException('La empresa no existe');
        }

        return $empresa;
    }
}