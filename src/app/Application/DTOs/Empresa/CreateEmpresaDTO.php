<?php

namespace App\Application\DTOs\Empresa;

/**
 * DTO para crear una nueva empresa.
 * Encapsula los datos necesarios para la creación de una empresa desde la capa de presentación.
 */
class CreateEmpresaDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $email,
        public readonly string $planId
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
            planId: $data['plan_id'] ?? ''
        );
    }

    /**
     * Convierte el DTO a array.
     */
    public function toArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'email' => $this->email,
            'plan_id' => $this->planId,
        ];
    }
}