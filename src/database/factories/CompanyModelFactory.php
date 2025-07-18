<?php

namespace Database\Factories;

use App\Infrastructure\Models\PlanModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Models\CompanyModel>
 */
class CompanyModelFactory extends Factory
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
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'plan_id' => PlanModel::factory(),
        ];
    }
}
