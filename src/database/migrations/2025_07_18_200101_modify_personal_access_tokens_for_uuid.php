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
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropIndex(['tokenable_type', 'tokenable_id']);
            $table->dropColumn('tokenable_id');
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->string('tokenable_id')->after('id');
            $table->index(['tokenable_type', 'tokenable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropIndex(['tokenable_type', 'tokenable_id']);
            $table->dropColumn('tokenable_id');
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->unsignedBigInteger('tokenable_id')->after('id');
            $table->index(['tokenable_type', 'tokenable_id']);
        });
    }
};
