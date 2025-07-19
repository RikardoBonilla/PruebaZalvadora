<?php

namespace App\Application\UseCases\Empresa;

use App\Domain\Empresa\Repositories\EmpresaRepositoryInterface;
use App\Domain\Empresa\ValueObjects\EmpresaId;

/**
 * Caso de uso para eliminar una empresa.
 * Maneja la lógica de negocio para la eliminación de empresas con validaciones correspondientes.
 */
class DeleteEmpresaUseCase
{
    public function __construct(
        private EmpresaRepositoryInterface $empresaRepository
    ) {
    }

    /**
     * Ejecuta el caso de uso para eliminar una empresa.
     */
    public function execute(string $empresaId): void
    {
        $empresaIdVO = EmpresaId::fromString($empresaId);
        
        // Verificar que la empresa existe
        $empresa = $this->empresaRepository->findById($empresaIdVO);
        if (!$empresa) {
            throw new \InvalidArgumentException('La empresa no existe');
        }

        // Verificar si tiene usuarios activos
        $usuariosActivos = $this->empresaRepository->getUsuariosActivosCount($empresaIdVO);
        if ($usuariosActivos > 0) {
            throw new \DomainException(
                sprintf('No se puede eliminar la empresa porque tiene %d usuarios activos', $usuariosActivos)
            );
        }

        // Eliminar la empresa
        $this->empresaRepository->delete($empresaIdVO);
    }
}