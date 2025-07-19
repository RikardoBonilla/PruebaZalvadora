<?php

namespace App\Domain\UsuarioEmpresa\Repositories;

use App\Domain\UsuarioEmpresa\Entities\UsuarioEmpresa;
use App\Domain\UsuarioEmpresa\ValueObjects\UsuarioEmpresaId;
use App\Domain\Empresa\ValueObjects\EmpresaId;
use App\Domain\Shared\ValueObjects\Email;

/**
 * Interfaz del repositorio para la entidad UsuarioEmpresa.
 * Define las operaciones de persistencia disponibles en el dominio.
 */
interface UsuarioEmpresaRepositoryInterface
{
    /**
     * Guarda un usuario de empresa (crear o actualizar).
     */
    public function save(UsuarioEmpresa $usuario): void;

    /**
     * Busca un usuario por su ID.
     */
    public function findById(UsuarioEmpresaId $id): ?UsuarioEmpresa;

    /**
     * Busca un usuario por su email.
     */
    public function findByEmail(Email $email): ?UsuarioEmpresa;

    /**
     * Obtiene todos los usuarios de una empresa con paginación.
     */
    public function findByEmpresa(EmpresaId $empresaId, int $page = 1, int $limit = 10): array;

    /**
     * Obtiene todos los usuarios activos de una empresa.
     */
    public function findActivosByEmpresa(EmpresaId $empresaId): array;

    /**
     * Elimina un usuario.
     */
    public function delete(UsuarioEmpresaId $id): void;

    /**
     * Verifica si existe un usuario con el email dado.
     */
    public function existsByEmail(Email $email): bool;

    /**
     * Verifica si existe un usuario con el email dado, excluyendo un ID específico.
     */
    public function existsByEmailExcludingId(Email $email, UsuarioEmpresaId $excludeId): bool;

    /**
     * Obtiene el conteo de usuarios activos de una empresa.
     */
    public function countActivosByEmpresa(EmpresaId $empresaId): int;

    /**
     * Obtiene el conteo total de usuarios de una empresa.
     */
    public function countByEmpresa(EmpresaId $empresaId): int;

    /**
     * Obtiene usuarios por rol en una empresa.
     */
    public function findByEmpresaAndRol(EmpresaId $empresaId, string $rol): array;

    /**
     * Verifica si una empresa tiene al menos un administrador activo.
     */
    public function empresaTieneAdminActivo(EmpresaId $empresaId): bool;
}