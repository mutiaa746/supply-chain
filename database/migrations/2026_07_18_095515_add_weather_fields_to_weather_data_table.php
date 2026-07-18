<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weather_data', function (Blueprint $table) {

            $table->decimal('rain',5,2)->default(0);

            $table->decimal('humidity',5,2)->default(0);

            $table->string('storm_risk')->default('Low');

        });
    }

    public function down(): void
    {
        Schema::table('weather_data', function (Blueprint $table) {

            $table->dropColumn([
                'rain',
                'humidity',
                'storm_risk'
            ]);

        });
    }
};