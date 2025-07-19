<?php

namespace App\Domain\Empresa\ValueObjects;

/**
 * Value Object para el nombre de una empresa.
 * Encapsula las reglas de validación y lógica relacionada con nombres de empresa.
 */
class EmpresaNombre
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = trim($value);
    }

    /**
     * Valida que el nombre de la empresa cumpla con las reglas de negocio.
     */
    private function validate(string $value): void
    {
        $value = trim($value);
        
        if (empty($value)) {
            throw new \InvalidArgumentException('El nombre de la empresa no puede estar vacío');
        }

        if (strlen($value) < 2) {
            throw new \InvalidArgumentException('El nombre de la empresa debe tener al menos 2 caracteres');
        }

        if (strlen($value) > 255) {
            throw new \InvalidArgumentException('El nombre de la empresa no puede tener más de 255 caracteres');
        }

        // Validar que no contenga solo números
        if (is_numeric($value)) {
            throw new \InvalidArgumentException('El nombre de la empresa no puede ser solo números');
        }

        // Validar caracteres permitidos (letras, números, espacios y algunos símbolos básicos)
        if (!preg_match('/^[a-zA-ZÀ-ÿ0-9\s\-\.\,\&\(\)]+$/u', $value)) {
            throw new \InvalidArgumentException('El nombre de la empresa contiene caracteres no válidos');
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
     * Compara si dos nombres de empresa son iguales.
     */
    public function equals(EmpresaNombre $other): bool
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
     * Crea un EmpresaNombre desde un string.
     */
    public static function fromString(string $nombre): self
    {
        return new self($nombre);
    }
}