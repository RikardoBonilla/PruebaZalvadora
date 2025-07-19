<?php

namespace App\Application\DTOs\Empresa;

/**
 * DTO para actualizar una empresa existente.
 * Encapsula los datos modificables de una empresa desde la capa de presentación.
 */
class UpdateEmpresaDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $email,
        public readonly ?string $planId = null,
        public readonly ?string $motivoCambio = null
    ) {
    }

    /**
     * Crea el DTO desde un array de datos.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            nombre: $data['nombre'] ?? '',
            email: $data['email'] ?? '',
            planId: $data['plan_id'] ?? null,
            motivoCambio: $data['motivo_cambio'] ?? null
        );
    }

    /**
     * Convierte el DTO a array.
     */
    public function toArray(): array
    {
        $data = [
            'nombre' => $this->nombre,
            'email' => $this->email,
        ];

        if ($this->planId !== null) {
            $data['plan_id'] = $this->planId;
        }

        if ($this->motivoCambio !== null) {
            $data['motivo_cambio'] = $this->motivoCambio;
        }

        return $data;
    }

    /**
     * Verifica si se debe cambiar el plan.
     */
    public function debeCambiarPlan(): bool
    {
        return $this->planId !== null;
    }
}