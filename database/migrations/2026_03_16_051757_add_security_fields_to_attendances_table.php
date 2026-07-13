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
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('verification_photo')->nullable()->after('method');
            $table->boolean('is_fake_gps')->default(false)->after('within_geofence');
            $table->string('device_id')->nullable()->after('is_fake_gps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['verification_photo', 'is_fake_gps', 'device_id']);
        });
    }
};
