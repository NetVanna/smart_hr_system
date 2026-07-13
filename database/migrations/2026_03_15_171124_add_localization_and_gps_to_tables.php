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
        Schema::table('companies', function (Blueprint $table) {
            // Localization
            $table->string('base_currency')->default('USD')->after('address');
            $table->decimal('exchange_rate', 10, 2)->default(4100)->after('base_currency');
            
            // Geofencing
            $table->decimal('latitude', 10, 8)->nullable()->after('exchange_rate');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->decimal('geofence_radius', 8, 2)->default(100)->after('longitude'); // radius in meters
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('location');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->boolean('within_geofence')->default(true)->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['base_currency', 'exchange_rate', 'latitude', 'longitude', 'geofence_radius']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'within_geofence']);
        });
    }
};
