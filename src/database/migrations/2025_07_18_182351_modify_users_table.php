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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->first();
            $table->uuid('company_id')->after('email_verified_at');
            $table->enum('role', ['admin', 'user'])->default('user')->after('company_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->index(['company_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropIndex(['company_id', 'role']);
            $table->dropColumn(['company_id', 'role']);
            $table->dropColumn('id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->id()->first();
        });
    }
};
