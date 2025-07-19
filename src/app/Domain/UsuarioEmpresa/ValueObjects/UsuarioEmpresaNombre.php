<?php

namespace App\Domain\UsuarioEmpresa\ValueObjects;

/**
 * Value Object para el nombre de un usuario de empresa.
 * Encapsula las reglas de validación y lógica relacionada con nombres de usuario.
 */
class UsuarioEmpresaNombre
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = trim($value);
    }

    /**
     * Valida que el nombre del usuario cumpla con las reglas de negocio.
     */
    private function validate(string $value): void
    {
        $value = trim($value);
        
        if (empty($value)) {
            throw new \InvalidArgumentException('El nombre del usuario no puede estar vacío');
        }

        if (strlen($value) < 2) {
            throw new \InvalidArgumentException('El nombre del usuario debe tener al menos 2 caracteres');
        }

        if (strlen($value) > 100) {
            throw new \InvalidArgumentException('El nombre del usuario no puede tener más de 100 caracteres');
        }

        // Validar que no contenga solo números
        if (is_numeric($value)) {
            throw new \InvalidArgumentException('El nombre del usuario no puede ser solo números');
        }

        // Validar caracteres permitidos (letras, espacios y algunos caracteres especiales básicos)
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\.]+$/u', $value)) {
            throw new \InvalidArgumentException('El nombre del usuario contiene caracteres no válidos');
        }
    }

    /**
     * Obtiene el valor del nombre.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Compara si dos nombres de usuario son iguales.
     */
    public function equals(UsuarioEmpresaNombre $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Convierte el value object a string.
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Crea un UsuarioEmpresaNombre desde un string.
     */
    public static function fromString(string $nombre): self
    {
        return new self($nombre);
    }
}