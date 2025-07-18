<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('planes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('monthly_price_amount');
            $table->string('monthly_price_currency', 3)->default('USD');
            $table->integer('user_limit');
            $table->json('features')->nullable();
            $table->timestamps();

            $table->index(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes');
    }
};
