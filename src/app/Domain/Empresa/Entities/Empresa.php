<?php

namespace App\Domain\Empresa\Entities;

use App\Domain\Empresa\ValueObjects\EmpresaId;
use App\Domain\Empresa\ValueObjects\EmpresaNombre;
use App\Domain\Plan\ValueObjects\PlanId;
use App\Domain\Shared\ValueObjects\Email;
use DateTime;

/**
 * Entidad de dominio Empresa.
 * Representa una empresa tenant con su plan activo y lógica de negocio relacionada.
 */
class Empresa
{
    private EmpresaId $id;
    private EmpresaNombre $nombre;
    private Email $email;
    private PlanId $planId;
    private DateTime $fechaSuscripcion;
    private DateTime $fechaCreacion;
    private DateTime $fechaActualizacion;

    public function __construct(
        EmpresaId $id,
        EmpresaNombre $nombre,
        Email $email,
        PlanId $planId,
        DateTime $fechaSuscripcion,
        ?DateTime $fechaCreacion = null,
        ?DateTime $fechaActualizacion = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->planId = $planId;
        $this->fechaSuscripcion = $fechaSuscripcion;
        $this->fechaCreacion = $fechaCreacion ?? new DateTime();
        $this->fechaActualizacion = $fechaActualizacion ?? new DateTime();
    }

    /**
     * Crea una nueva empresa con un plan inicial.
     */
    public static function crear(
        EmpresaNombre $nombre,
        Email $email,
        PlanId $planId
    ): self {
        return new self(
            EmpresaId::generar(),
            $nombre,
            $email,
            $planId,
            new DateTime()
        );
    }

    /**
     * Cambia el plan de la empresa.
     * Esta operación debe generar un evento de dominio y actualizar el historial.
     */
    public function cambiarPlan(PlanId $nuevoPlanId, string $motivoCambio): void
    {
        if ($this->planId->equals($nuevoPlanId)) {
            throw new \DomainException('El nuevo plan debe ser diferente al plan actual');
        }

        $this->planId = $nuevoPlanId;
        $this->fechaSuscripcion = new DateTime();
        $this->fechaActualizacion = new DateTime();
    }

    /**
     * Actualiza la información básica de la empresa.
     */
    public function actualizar(EmpresaNombre $nombre, Email $email): void
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->fechaActualizacion = new DateTime();
    }

    /**
     * Verifica si la empresa puede agregar más usuarios según su plan.
     */
    public function puedeAgregarUsuarios(int $usuariosActuales, int $limiteUsuarios): bool
    {
        return $usuariosActuales < $limiteUsuarios;
    }

    // Getters
    public function getId(): EmpresaId
    {
        return $this->id;
    }

    public function getNombre(): EmpresaNombre
    {
        return $this->nombre;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPlanId(): PlanId
    {
        return $this->planId;
    }

    public function getFechaSuscripcion(): DateTime
    {
        return $this->fechaSuscripcion;
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
            'plan_id' => $this->planId->getValue(),
            'fecha_suscripcion' => $this->fechaSuscripcion->format('Y-m-d H:i:s'),
            'created_at' => $this->fechaCreacion->format('Y-m-d H:i:s'),
            'updated_at' => $this->fechaActualizacion->format('Y-m-d H:i:s'),
        ];
    }
}