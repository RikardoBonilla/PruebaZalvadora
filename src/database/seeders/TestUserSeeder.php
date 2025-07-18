<?php

namespace Database\Seeders;

use App\Infrastructure\Models\CompanyModel;
use App\Infrastructure\Models\PlanModel;
use App\Infrastructure\Models\UserModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear un plan de prueba
        $plan = PlanModel::create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Plan Básico',
            'monthly_price_amount' => 2999,
            'monthly_price_currency' => 'USD',
            'user_limit' => 10,
            'features' => ['Dashboard básico', 'Soporte por email', 'API access']
        ]);

        // Crear una empresa de prueba
        $company = CompanyModel::create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Empresa Demo',
            'email' => 'demo@empresa.com',
            'plan_id' => $plan->id
        ]);

        // Crear usuario administrador
        UserModel::create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'company_id' => $company->id,
            'role' => 'admin',
            'password' => Hash::make('password')
        ]);

        // Crear usuario normal
        UserModel::create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Normal User',
            'email' => 'user@example.com',
            'company_id' => $company->id,
            'role' => 'user',
            'password' => Hash::make('password')
        ]);

        // Crear algunos planes adicionales
        PlanModel::create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Plan Premium',
            'monthly_price_amount' => 4999,
            'monthly_price_currency' => 'USD',
            'user_limit' => 25,
            'features' => ['Dashboard avanzado', 'Soporte prioritario', 'API ilimitado', 'Reportes']
        ]);

        PlanModel::create([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Plan Enterprise',
            'monthly_price_amount' => 9999,
            'monthly_price_currency' => 'USD',
            'user_limit' => 100,
            'features' => ['Todo incluido', 'Soporte 24/7', 'API dedicado', 'Reportes personalizados', 'Integración SSO']
        ]);
    }
}
