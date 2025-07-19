<?php

namespace App\Domain\Empresa\ValueObjects;

use App\Domain\Shared\ValueObjects\Id;

/**
 * Value Object para el ID de una empresa.
 * Extiende la clase base Id para mantener consistencia en el dominio.
 */
class EmpresaId extends Id
{
    /**
     * Genera un nuevo ID único para una empresa.
     */
    public static function generar(): self
    {
        return new self(self::generateUuid());
    }

    /**
     * Crea un EmpresaId desde un string existente.
     */
    public static function fromString(string $id): self
    {
        return new self($id);
    }
}