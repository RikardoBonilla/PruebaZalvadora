<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Plan\ValueObjects;

use App\Domain\Plan\ValueObjects\UserLimit;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UserLimitTest extends TestCase
{
    public function test_can_create_valid_user_limit(): void
    {
        $userLimit = new UserLimit(10);

        $this->assertEquals(10, $userLimit->value);
    }

    public function test_cannot_create_user_limit_with_zero_or_negative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User limit must be at least 1');

        new UserLimit(0);
    }

    public function test_can_check_if_limit_is_exceeded(): void
    {
        $userLimit = new UserLimit(5);

        $this->assertFalse($userLimit->isExceeded(3));
        $this->assertFalse($userLimit->isExceeded(5));
        $this->assertTrue($userLimit->isExceeded(6));
    }

    public function test_can_check_if_can_add_users(): void
    {
        $userLimit = new UserLimit(10);

        $this->assertTrue($userLimit->canAddUsers(5, 3));
        $this->assertTrue($userLimit->canAddUsers(5, 5));
        $this->assertFalse($userLimit->canAddUsers(5, 6));
    }

    public function test_can_compare_user_limits(): void
    {
        $limit1 = new UserLimit(10);
        $limit2 = new UserLimit(10);
        $limit3 = new UserLimit(5);

        $this->assertTrue($limit1->equals($limit2));
        $this->assertFalse($limit1->equals($limit3));
    }
}