<?php

namespace App\Infrastructure\Repositories;

use App\Domain\UsuarioEmpresa\Entities\UsuarioEmpresa;
use App\Domain\UsuarioEmpresa\Repositories\UsuarioEmpresaRepositoryInterface;
use App\Domain\UsuarioEmpresa\ValueObjects\UsuarioEmpresaId;
use App\Domain\UsuarioEmpresa\ValueObjects\UsuarioEmpresaNombre;
use App\Domain\UsuarioEmpresa\ValueObjects\UsuarioEmpresaRol;
use App\Domain\Empresa\ValueObjects\EmpresaId;
use App\Domain\Shared\ValueObjects\Email;
use App\Infrastructure\Models\UsuarioEmpresaModel;
use DateTime;

/**
 * Implementación concreta del repositorio de usuarios de empresa usando Eloquent.
 * Actúa como puente entre el dominio y la persistencia de datos.
 */
class EloquentUsuarioEmpresaRepository implements UsuarioEmpresaRepositoryInterface
{
    private UsuarioEmpresaModel $model;

    public function __construct(UsuarioEmpresaModel $model)
    {
        $this->model = $model;
    }

    /**
     * Guarda un usuario de empresa (crear o actualizar).
     */
    public function save(UsuarioEmpresa $usuario): void
    {
        $data = $usuario->toArray();
        
        $this->model->updateOrCreate(
            ['id' => $data['id']],
            $data
        );
    }

    /**
     * Busca un usuario por su ID.
     */
    public function findById(UsuarioEmpresaId $id): ?UsuarioEmpresa
    {
        $usuarioModel = $this->model->find($id->getValue());
        
        if (!$usuarioModel) {
            return null;
        }

        return $this->toDomainEntity($usuarioModel);
    }

    /**
     * Busca un usuario por su email.
     */
    public function findByEmail(Email $email): ?UsuarioEmpresa
    {
        $usuarioModel = $this->model->where('email', $email->getValue())->first();
        
        if (!$usuarioModel) {
            return null;
        }

        return $this->toDomainEntity($usuarioModel);
    }

    /**
     * Obtiene todos los usuarios de una empresa con paginación.
     */
    public function findByEmpresa(EmpresaId $empresaId, int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        
        $usuarioModels = $this->model
            ->where('empresa_id', $empresaId->getValue())
            ->offset($offset)
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();

        return $usuarioModels->map(function ($usuarioModel) {
            return $this->toDomainEntity($usuarioModel);
        })->toArray();
    }

    /**
     * Obtiene todos los usuarios activos de una empresa.
     */
    public function findActivosByEmpresa(EmpresaId $empresaId): array
    {
        $usuarioModels = $this->model
            ->where('empresa_id', $empresaId->getValue())
            ->where('activo', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return $usuarioModels->map(function ($usuarioModel) {
            return $this->toDomainEntity($usuarioModel);
        })->toArray();
    }

    /**
     * Elimina un usuario.
     */
    public function delete(UsuarioEmpresaId $id): void
    {
        $this->model->where('id', $id->getValue())->delete();
    }

    /**
     * Verifica si existe un usuario con el email dado.
     */
    public function existsByEmail(Email $email): bool
    {
        return $this->model->where('email', $email->getValue())->exists();
    }

    /**
     * Verifica si existe un usuario con el email dado, excluyendo un ID específico.
     */
    public function existsByEmailExcludingId(Email $email, UsuarioEmpresaId $excludeId): bool
    {
        return $this->model
            ->where('email', $email->getValue())
            ->where('id', '!=', $excludeId->getValue())
            ->exists();
    }

    /**
     * Obtiene el conteo de usuarios activos de una empresa.
     */
    public function countActivosByEmpresa(EmpresaId $empresaId): int
    {
        return $this->model
            ->where('empresa_id', $empresaId->getValue())
            ->where('activo', true)
            ->count();
    }

    /**
     * Obtiene el conteo total de usuarios de una empresa.
     */
    public function countByEmpresa(EmpresaId $empresaId): int
    {
        return $this->model
            ->where('empresa_id', $empresaId->getValue())
            ->count();
    }

    /**
     * Obtiene usuarios por rol en una empresa.
     */
    public function findByEmpresaAndRol(EmpresaId $empresaId, string $rol): array
    {
        $usuarioModels = $this->model
            ->where('empresa_id', $empresaId->getValue())
            ->where('rol', $rol)
            ->where('activo', true)
            ->get();

        return $usuarioModels->map(function ($usuarioModel) {
            return $this->toDomainEntity($usuarioModel);
        })->toArray();
    }

    /**
     * Verifica si una empresa tiene al menos un administrador activo.
     */
    public function empresaTieneAdminActivo(EmpresaId $empresaId): bool
    {
        return $this->model
            ->where('empresa_id', $empresaId->getValue())
            ->where('rol', 'admin')
            ->where('activo', true)
            ->exists();
    }

    /**
     * Convierte un modelo Eloquent a entidad de dominio.
     */
    private function toDomainEntity(UsuarioEmpresaModel $usuarioModel): UsuarioEmpresa
    {
        return new UsuarioEmpresa(
            UsuarioEmpresaId::fromString($usuarioModel->id),
            UsuarioEmpresaNombre::fromString($usuarioModel->nombre),
            Email::fromString($usuarioModel->email),
            $usuarioModel->password,
            EmpresaId::fromString($usuarioModel->empresa_id),
            UsuarioEmpresaRol::fromString($usuarioModel->rol),
            $usuarioModel->activo,
            new DateTime($usuarioModel->created_at),
            new DateTime($usuarioModel->updated_at)
        );
    }
}