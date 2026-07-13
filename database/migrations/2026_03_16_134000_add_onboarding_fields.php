<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add telegram_username to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('telegram_username')->nullable()->after('role');
        });

        // Add onboarding_step to companies
        Schema::table('companies', function (Blueprint $table) {
            $table->string('onboarding_step')->default('introduction')->after('subscription_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('telegram_username');
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('onboarding_step');
        });
    }
};
