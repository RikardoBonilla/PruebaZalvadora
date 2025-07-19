<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\Repositories\EmpresaRepositoryInterface;
use App\Domain\Empresa\ValueObjects\EmpresaId;
use App\Domain\Empresa\ValueObjects\EmpresaNombre;
use App\Domain\Plan\ValueObjects\PlanId;
use App\Domain\Shared\ValueObjects\Email;
use App\Infrastructure\Models\EmpresaModel;
use DateTime;

/**
 * Implementación concreta del repositorio de empresas usando Eloquent.
 * Actúa como puente entre el dominio y la persistencia de datos.
 */
class EloquentEmpresaRepository implements EmpresaRepositoryInterface
{
    private EmpresaModel $model;

    public function __construct(EmpresaModel $model)
    {
        $this->model = $model;
    }

    /**
     * Guarda una empresa (crear o actualizar).
     */
    public function save(Empresa $empresa): void
    {
        $data = $empresa->toArray();
        
        $this->model->updateOrCreate(
            ['id' => $data['id']],
            $data
        );
    }

    /**
     * Busca una empresa por su ID.
     */
    public function findById(EmpresaId $id): ?Empresa
    {
        $empresaModel = $this->model->find($id->getValue());
        
        if (!$empresaModel) {
            return null;
        }

        return $this->toDomainEntity($empresaModel);
    }

    /**
     * Busca una empresa por su email.
     */
    public function findByEmail(Email $email): ?Empresa
    {
        $empresaModel = $this->model->where('email', $email->getValue())->first();
        
        if (!$empresaModel) {
            return null;
        }

        return $this->toDomainEntity($empresaModel);
    }

    /**
     * Obtiene todas las empresas con paginación.
     */
    public function findAll(int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        
        $empresaModels = $this->model
            ->with('plan')
            ->offset($offset)
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();

        return $empresaModels->map(function ($empresaModel) {
            return $this->toDomainEntity($empresaModel);
        })->toArray();
    }

    /**
     * Elimina una empresa.
     */
    public function delete(EmpresaId $id): void
    {
        $this->model->where('id', $id->getValue())->delete();
    }

    /**
     * Verifica si existe una empresa con el email dado.
     */
    public function existsByEmail(Email $email): bool
    {
        return $this->model->where('email', $email->getValue())->exists();
    }

    /**
     * Verifica si existe una empresa con el email dado, excluyendo un ID específico.
     */
    public function existsByEmailExcludingId(Email $email, EmpresaId $excludeId): bool
    {
        return $this->model
            ->where('email', $email->getValue())
            ->where('id', '!=', $excludeId->getValue())
            ->exists();
    }

    /**
     * Obtiene el conteo total de empresas.
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Obtiene empresas por plan.
     */
    public function findByPlanId(string $planId): array
    {
        $empresaModels = $this->model
            ->where('plan_id', $planId)
            ->with('plan')
            ->get();

        return $empresaModels->map(function ($empresaModel) {
            return $this->toDomainEntity($empresaModel);
        })->toArray();
    }

    /**
     * Obtiene el número de usuarios activos de una empresa.
     */
    public function getUsuariosActivosCount(EmpresaId $empresaId): int
    {
        return $this->model
            ->find($empresaId->getValue())
            ?->usuarios()
            ->where('activo', true)
            ->count() ?? 0;
    }

    /**
     * Convierte un modelo Eloquent a entidad de dominio.
     */
    private function toDomainEntity(EmpresaModel $empresaModel): Empresa
    {
        return new Empresa(
            EmpresaId::fromString($empresaModel->id),
            EmpresaNombre::fromString($empresaModel->nombre),
            Email::fromString($empresaModel->email),
            PlanId::fromString($empresaModel->plan_id),
            new DateTime($empresaModel->fecha_suscripcion),
            new DateTime($empresaModel->created_at),
            new DateTime($empresaModel->updated_at)
        );
    }
}