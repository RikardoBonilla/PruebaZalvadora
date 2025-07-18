<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Plan\Entities;

use App\Domain\Plan\Entities\Plan;
use App\Domain\Plan\ValueObjects\Features;
use App\Domain\Plan\ValueObjects\PlanName;
use App\Domain\Plan\ValueObjects\UserLimit;
use App\Domain\Shared\ValueObjects\Money;
use PHPUnit\Framework\TestCase;

class PlanTest extends TestCase
{
    public function test_can_create_plan(): void
    {
        $plan = Plan::create(
            name: new PlanName('Basic Plan'),
            monthlyPrice: new Money(2999, 'USD'),
            userLimit: new UserLimit(10),
            features: new Features(['Feature A', 'Feature B'])
        );

        $this->assertEquals('Basic Plan', $plan->getName()->value);
        $this->assertEquals(2999, $plan->getMonthlyPrice()->amount);
        $this->assertEquals('USD', $plan->getMonthlyPrice()->currency);
        $this->assertEquals(10, $plan->getUserLimit()->value);
        $this->assertEquals(['Feature A', 'Feature B'], $plan->getFeatures()->toArray());
        $this->assertNotNull($plan->getId());
        $this->assertNotNull($plan->getCreatedAt());
        $this->assertNull($plan->getUpdatedAt());
    }

    public function test_can_update_plan(): void
    {
        $plan = Plan::create(
            name: new PlanName('Basic Plan'),
            monthlyPrice: new Money(2999, 'USD'),
            userLimit: new UserLimit(10),
            features: new Features(['Feature A'])
        );

        $originalUpdatedAt = $plan->getUpdatedAt();

        $plan->update(
            name: new PlanName('Premium Plan'),
            monthlyPrice: new Money(4999, 'USD'),
            userLimit: new UserLimit(25),
            features: new Features(['Feature A', 'Feature B', 'Feature C'])
        );

        $this->assertEquals('Premium Plan', $plan->getName()->value);
        $this->assertEquals(4999, $plan->getMonthlyPrice()->amount);
        $this->assertEquals(25, $plan->getUserLimit()->value);
        $this->assertEquals(['Feature A', 'Feature B', 'Feature C'], $plan->getFeatures()->toArray());
        $this->assertNotEquals($originalUpdatedAt, $plan->getUpdatedAt());
    }

    public function test_can_check_if_plan_can_accommodate_users(): void
    {
        $plan = Plan::create(
            name: new PlanName('Basic Plan'),
            monthlyPrice: new Money(2999, 'USD'),
            userLimit: new UserLimit(10),
            features: new Features(['Feature A'])
        );

        $this->assertTrue($plan->canAccommodateUsers(5));
        $this->assertTrue($plan->canAccommodateUsers(10));
        $this->assertFalse($plan->canAccommodateUsers(11));
    }
}