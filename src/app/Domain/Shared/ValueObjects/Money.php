<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObjects;

use InvalidArgumentException;

/**
 * Objeto de valor Money (Dinero)
 * 
 * Representa una cantidad monetaria inmutable con validaciones de dominio.
 * Utiliza centavos como unidad base para evitar problemas de precisión decimal.
 * Garantiza que las cantidades siempre sean positivas y tengan una moneda válida.
 */
final readonly class Money
{
    /**
     * Constructor del valor monetario
     * 
     * @param int $amount Cantidad en centavos (debe ser positiva)
     * @param string $currency Código de moneda (por defecto USD)
     * @throws InvalidArgumentException si la cantidad es negativa o la moneda está vacía
     */
    public function __construct(
        public int $amount,
        public string $currency = 'USD'
    ) {
        if ($amount < 0) {
            throw new InvalidArgumentException('Amount cannot be negative');
        }

        if (empty($currency)) {
            throw new InvalidArgumentException('Currency cannot be empty');
        }
    }

    /**
     * Compara si dos objetos Money son iguales
     * 
     * @param Money $other Otro objeto Money para comparar
     * @return bool true si tienen la misma cantidad y moneda
     */
    public function equals(Money $other): bool
    {
        return $this->amount === $other->amount && $this->currency === $other->currency;
    }

    /**
     * Convierte el objeto Money a un array asociativo
     * 
     * @return array Representación en array con amount y currency
     */
    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
        ];
    }

    /**
     * Convierte el objeto Money a una representación de cadena
     * 
     * @return string Formato "cantidad moneda" (ej: "1999 USD")
     */
    public function __toString(): string
    {
        return sprintf('%d %s', $this->amount, $this->currency);
    }
}