<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Models\PlanModel>
 */
class PlanModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->words(2, true) . ' Plan',
            'monthly_price_amount' => $this->faker->numberBetween(999, 9999),
            'monthly_price_currency' => 'USD',
            'user_limit' => $this->faker->numberBetween(1, 100),
            'features' => $this->faker->words(3),
        ];
    }
}
