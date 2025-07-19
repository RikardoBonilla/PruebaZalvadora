<?php

namespace App\Domain\UsuarioEmpresa\ValueObjects;

/**
 * Value Object para el rol de un usuario de empresa.
 * Define los roles disponibles y lógica relacionada con permisos.
 */
class UsuarioEmpresaRol
{
    public const ADMIN = 'admin';
    public const USUARIO = 'usuario';

    private const ROLES_VALIDOS = [
        self::ADMIN,
        self::USUARIO,
    ];

    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    /**
     * Valida que el rol sea válido.
     */
    private function validate(string $value): void
    {
        if (!in_array($value, self::ROLES_VALIDOS, true)) {
            throw new \InvalidArgumentException(
                sprintf('El rol "%s" no es válido. Roles válidos: %s', $value, implode(', ', self::ROLES_VALIDOS))
            );
        }
    }

    /**
     * Crea un rol de administrador.
     */
    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    /**
     * Crea un rol de usuario.
     */
    public static function usuario(): self
    {
        return new self(self::USUARIO);
    }

    /**
     * Crea un rol desde un string.
     */
    public static function fromString(string $rol): self
    {
        return new self($rol);
    }

    /**
     * Verifica si es un rol de administrador.
     */
    public function esAdmin(): bool
    {
        return $this->value === self::ADMIN;
    }

    /**
     * Verifica si es un rol de usuario regular.
     */
    public function esUsuario(): bool
    {
        return $this->value === self::USUARIO;
    }

    /**
     * Obtiene el valor del rol.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Compara si dos roles son iguales.
     */
    public function equals(UsuarioEmpresaRol $other): bool
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
     * Obtiene todos los roles válidos.
     */
    public static function getRolesValidos(): array
    {
        return self::ROLES_VALIDOS;
    }
}