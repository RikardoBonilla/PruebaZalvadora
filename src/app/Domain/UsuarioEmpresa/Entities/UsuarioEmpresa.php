<?php

namespace App\Domain\UsuarioEmpresa\Entities;

use App\Domain\UsuarioEmpresa\ValueObjects\UsuarioEmpresaId;
use App\Domain\UsuarioEmpresa\ValueObjects\UsuarioEmpresaNombre;
use App\Domain\UsuarioEmpresa\ValueObjects\UsuarioEmpresaRol;
use App\Domain\Empresa\ValueObjects\EmpresaId;
use App\Domain\Shared\ValueObjects\Email;
use DateTime;

/**
 * Entidad de dominio UsuarioEmpresa.
 * Representa un usuario interno de una empresa con validación de límites según el plan vigente.
 */
class UsuarioEmpresa
{
    private UsuarioEmpresaId $id;
    private UsuarioEmpresaNombre $nombre;
    private Email $email;
    private string $password;
    private EmpresaId $empresaId;
    private UsuarioEmpresaRol $rol;
    private bool $activo;
    private DateTime $fechaCreacion;
    private DateTime $fechaActualizacion;

    public function __construct(
        UsuarioEmpresaId $id,
        UsuarioEmpresaNombre $nombre,
        Email $email,
        string $password,
        EmpresaId $empresaId,
        UsuarioEmpresaRol $rol,
        bool $activo = true,
        ?DateTime $fechaCreacion = null,
        ?DateTime $fechaActualizacion = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->empresaId = $empresaId;
        $this->rol = $rol;
        $this->activo = $activo;
        $this->fechaCreacion = $fechaCreacion ?? new DateTime();
        $this->fechaActualizacion = $fechaActualizacion ?? new DateTime();
    }

    /**
     * Crea un nuevo usuario de empresa.
     */
    public static function crear(
        UsuarioEmpresaNombre $nombre,
        Email $email,
        string $password,
        EmpresaId $empresaId,
        UsuarioEmpresaRol $rol
    ): self {
        return new self(
            UsuarioEmpresaId::generar(),
            $nombre,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $empresaId,
            $rol
        );
    }

    /**
     * Actualiza la información básica del usuario.
     */
    public function actualizar(
        UsuarioEmpresaNombre $nombre,
        Email $email,
        ?UsuarioEmpresaRol $rol = null
    ): void {
        $this->nombre = $nombre;
        $this->email = $email;
        
        if ($rol !== null) {
            $this->rol = $rol;
        }
        
        $this->fechaActualizacion = new DateTime();
    }

    /**
     * Cambia la contraseña del usuario.
     */
    public function cambiarPassword(string $nuevaPassword): void
    {
        $this->password = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        $this->fechaActualizacion = new DateTime();
    }

    /**
     * Activa el usuario.
     */
    public function activar(): void
    {
        $this->activo = true;
        $this->fechaActualizacion = new DateTime();
    }

    /**
     * Desactiva el usuario.
     */
    public function desactivar(): void
    {
        $this->activo = false;
        $this->fechaActualizacion = new DateTime();
    }

    /**
     * Verifica si el usuario es administrador.
     */
    public function esAdministrador(): bool
    {
        return $this->rol->esAdmin();
    }

    /**
     * Verifica si el usuario está activo.
     */
    public function estaActivo(): bool
    {
        return $this->activo;
    }

    // Getters
    public function getId(): UsuarioEmpresaId
    {
        return $this->id;
    }

    public function getNombre(): UsuarioEmpresaNombre
    {
        return $this->nombre;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmpresaId(): EmpresaId
    {
        return $this->empresaId;
    }

    public function getRol(): UsuarioEmpresaRol
    {
        return $this->rol;
    }

    public function isActivo(): bool
    {
        return $this->activo;
    }

    public function getFechaCreacion(): DateTime
    {
        return $this->fechaCreacion;
    }

    public function getFechaActualizacion(): DateTime
    {
        return $this->fechaActualizacion;
    }

    /**
     * Convierte la entidad a array para persistencia.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'nombre' => $this->nombre->getValue(),
            'email' => $this->email->getValue(),
            'password' => $this->password,
            'empresa_id' => $this->empresaId->getValue(),
            'rol' => $this->rol->getValue(),
            'activo' => $this->activo,
            'created_at' => $this->fechaCreacion->format('Y-m-d H:i:s'),
            'updated_at' => $this->fechaActualizacion->format('Y-m-d H:i:s'),
        ];
    }
}