<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Plan\ValueObjects;

use App\Domain\Shared\ValueObjects\Money;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_can_create_valid_money(): void
    {
        $money = new Money(1000, 'USD');

        $this->assertEquals(1000, $money->amount);
        $this->assertEquals('USD', $money->currency);
    }

    public function test_cannot_create_money_with_negative_amount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount cannot be negative');

        new Money(-100, 'USD');
    }

    public function test_cannot_create_money_with_empty_currency(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Currency cannot be empty');

        new Money(1000, '');
    }

    public function test_can_compare_money_objects(): void
    {
        $money1 = new Money(1000, 'USD');
        $money2 = new Money(1000, 'USD');
        $money3 = new Money(2000, 'USD');

        $this->assertTrue($money1->equals($money2));
        $this->assertFalse($money1->equals($money3));
    }

    public function test_can_convert_to_array(): void
    {
        $money = new Money(1000, 'USD');
        $expected = ['amount' => 1000, 'currency' => 'USD'];

        $this->assertEquals($expected, $money->toArray());
    }

    public function test_can_convert_to_string(): void
    {
        $money = new Money(1000, 'USD');

        $this->assertEquals('1000 USD', (string) $money);
    }
}