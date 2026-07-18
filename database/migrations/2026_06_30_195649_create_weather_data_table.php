<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weather_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->decimal('temperature', 5, 2)->default(0);
            $table->decimal('wind_speed', 5, 2)->default(0);
            $table->decimal('rain', 5, 2)->default(0);
            $table->decimal('humidity', 5, 2)->default(0);
            $table->integer('weathercode')->default(0);
            $table->string('description')->nullable();
            $table->string('storm_risk')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_data');
    }
};