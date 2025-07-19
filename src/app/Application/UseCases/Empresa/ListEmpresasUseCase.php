<?php

namespace App\Application\UseCases\Empresa;

use App\Domain\Empresa\Repositories\EmpresaRepositoryInterface;

/**
 * Caso de uso para listar empresas con paginación.
 * Maneja la lógica de negocio para recuperar listados de empresas.
 */
class ListEmpresasUseCase
{
    public function __construct(
        private EmpresaRepositoryInterface $empresaRepository
    ) {
    }

    /**
     * Ejecuta el caso de uso para listar empresas.
     */
    public function execute(int $page = 1, int $limit = 10): array
    {
        if ($page < 1) {
            throw new \InvalidArgumentException('La página debe ser mayor a 0');
        }

        if ($limit < 1 || $limit > 100) {
            throw new \InvalidArgumentException('El límite debe estar entre 1 y 100');
        }

        $empresas = $this->empresaRepository->findAll($page, $limit);
        $total = $this->empresaRepository->count();

        return [
            'data' => $empresas,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total' => $total,
                'last_page' => ceil($total / $limit),
                'from' => ($page - 1) * $limit + 1,
                'to' => min($page * $limit, $total),
            ]
        ];
    }
}