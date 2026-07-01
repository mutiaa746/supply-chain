<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('economic_indicators', function (Blueprint $table) {
            $table->id();

            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->onDelete('cascade');

            $table->decimal('gdp', 15, 2)->nullable();
            $table->decimal('inflation_rate', 5, 2)->nullable();
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->decimal('unemployment_rate', 5, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('economic_indicators');
    }
};