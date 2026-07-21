<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('country_name');
            $table->string('country_code', 5)->unique();
            $table->string('capital')->nullable();
            $table->string('region')->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_code', 10)->nullable();
            $table->string('language')->nullable();
            $table->string('flag')->nullable();
            $table->bigInteger('population')->nullable();
            $table->decimal('gdp', 30, 2)->nullable();
            $table->decimal('inflation', 5, 2)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};