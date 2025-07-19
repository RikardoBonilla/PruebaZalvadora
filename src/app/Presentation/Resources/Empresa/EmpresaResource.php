<?php

namespace App\Presentation\Resources\Empresa;

use App\Domain\Empresa\Entities\Empresa;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource para formatear la respuesta de una empresa en la API.
 * Transforma la entidad de dominio en un formato JSON consistente.
 */
class EmpresaResource extends JsonResource
{
    /**
     * Transforma el recurso en un array.
     */
    public function toArray(Request $request): array
    {
        /** @var Empresa $empresa */
        $empresa = $this->resource;

        return [
            'id' => $empresa->getId()->getValue(),
            'nombre' => $empresa->getNombre()->getValue(),
            'email' => $empresa->getEmail()->getValue(),
            'plan_id' => $empresa->getPlanId()->getValue(),
            'fecha_suscripcion' => $empresa->getFechaSuscripcion()->format('Y-m-d H:i:s'),
            'created_at' => $empresa->getFechaCreacion()->format('Y-m-d H:i:s'),
            'updated_at' => $empresa->getFechaActualizacion()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Obtiene información adicional que debe ser incluida con el array de recursos.
     */
    public function with(Request $request): array
    {
        return [
            'status' => 'success',
        ];
    }
}