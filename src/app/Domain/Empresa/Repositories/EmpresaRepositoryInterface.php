<?php

namespace App\Domain\Empresa\Repositories;

use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\ValueObjects\EmpresaId;
use App\Domain\Shared\ValueObjects\Email;

/**
 * Interfaz del repositorio para la entidad Empresa.
 * Define las operaciones de persistencia disponibles en el dominio.
 */
interface EmpresaRepositoryInterface
{
    /**
     * Guarda una empresa (crear o actualizar).
     */
    public function save(Empresa $empresa): void;

    /**
     * Busca una empresa por su ID.
     */
    public function findById(EmpresaId $id): ?Empresa;

    /**
     * Busca una empresa por su email.
     */
    public function findByEmail(Email $email): ?Empresa;

    /**
     * Obtiene todas las empresas con paginación.
     */
    public function findAll(int $page = 1, int $limit = 10): array;

    /**
     * Elimina una empresa.
     */
    public function delete(EmpresaId $id): void;

    /**
     * Verifica si existe una empresa con el email dado.
     */
    public function existsByEmail(Email $email): bool;

    /**
     * Verifica si existe una empresa con el email dado, excluyendo un ID específico.
     */
    public function existsByEmailExcludingId(Email $email, EmpresaId $excludeId): bool;

    /**
     * Obtiene el conteo total de empresas.
     */
    public function count(): int;

    /**
     * Obtiene empresas por plan.
     */
    public function findByPlanId(string $planId): array;

    /**
     * Obtiene el número de usuarios activos de una empresa.
     */
    public function getUsuariosActivosCount(EmpresaId $empresaId): int;
}