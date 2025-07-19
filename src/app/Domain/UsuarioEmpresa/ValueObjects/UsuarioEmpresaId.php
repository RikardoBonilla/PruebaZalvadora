<?php

namespace App\Domain\UsuarioEmpresa\ValueObjects;

use App\Domain\Shared\ValueObjects\Id;

/**
 * Value Object para el ID de un usuario de empresa.
 * Extiende la clase base Id para mantener consistencia en el dominio.
 */
class UsuarioEmpresaId extends Id
{
    /**
     * Genera un nuevo ID único para un usuario de empresa.
     */
    public static function generar(): self
    {
        return new self(self::generateUuid());
    }

    /**
     * Crea un UsuarioEmpresaId desde un string existente.
     */
    public static function fromString(string $id): self
    {
        return new self($id);
    }
}