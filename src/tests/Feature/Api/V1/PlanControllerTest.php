<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use App\Infrastructure\Models\PlanModel;
use App\Infrastructure\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PlanControllerTest extends TestCase
{
    use RefreshDatabase;

    private UserModel $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = UserModel::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_can_list_plans(): void
    {
        Sanctum::actingAs($this->adminUser);

        PlanModel::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/plans');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'monthly_price' => ['amount', 'currency'],
                        'user_limit',
                        'features',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    public function test_can_create_plan(): void
    {
        Sanctum::actingAs($this->adminUser);

        $planData = [
            'name' => 'Test Plan',
            'monthly_price' => 2999,
            'currency' => 'USD',
            'user_limit' => 10,
            'features' => ['Feature A', 'Feature B'],
        ];

        $response = $this->postJson('/api/v1/plans', $planData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'monthly_price' => ['amount', 'currency'],
                'user_limit',
                'features',
                'created_at',
                'updated_at',
            ])
            ->assertJson([
                'name' => 'Test Plan',
                'monthly_price' => ['amount' => 2999, 'currency' => 'USD'],
                'user_limit' => 10,
                'features' => ['Feature A', 'Feature B'],
            ]);

        $this->assertDatabaseHas('plans', [
            'name' => 'Test Plan',
            'monthly_price_amount' => 2999,
            'monthly_price_currency' => 'USD',
            'user_limit' => 10,
        ]);
    }

    public function test_can_show_plan(): void
    {
        Sanctum::actingAs($this->adminUser);

        $plan = PlanModel::factory()->create();

        $response = $this->getJson("/api/v1/plans/{$plan->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $plan->id,
                'name' => $plan->name,
            ]);
    }

    public function test_can_update_plan(): void
    {
        Sanctum::actingAs($this->adminUser);

        $plan = PlanModel::factory()->create();

        $updateData = [
            'name' => 'Updated Plan',
            'monthly_price' => 3999,
            'currency' => 'USD',
            'user_limit' => 15,
            'features' => ['Updated Feature'],
        ];

        $response = $this->putJson("/api/v1/plans/{$plan->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $plan->id,
                'name' => 'Updated Plan',
                'monthly_price' => ['amount' => 3999, 'currency' => 'USD'],
                'user_limit' => 15,
                'features' => ['Updated Feature'],
            ]);

        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'name' => 'Updated Plan',
            'monthly_price_amount' => 3999,
            'user_limit' => 15,
        ]);
    }

    public function test_can_delete_plan(): void
    {
        Sanctum::actingAs($this->adminUser);

        $plan = PlanModel::factory()->create();

        $response = $this->deleteJson("/api/v1/plans/{$plan->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('plans', ['id' => $plan->id]);
    }

    public function test_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/plans');

        $response->assertStatus(401);
    }

    public function test_validates_create_plan_request(): void
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->postJson('/api/v1/plans', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'monthly_price',
                'currency',
                'user_limit',
                'features',
            ]);
    }
}