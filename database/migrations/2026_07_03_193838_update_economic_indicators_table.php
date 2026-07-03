<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('economic_indicators', function (Blueprint $table) {

            $table->dropColumn([
                'inflation_rate',
                'interest_rate',
                'unemployment_rate'
            ]);

            $table->decimal('inflation', 8, 2)->nullable()->after('gdp');
            $table->bigInteger('population')->nullable()->after('inflation');
            $table->decimal('exports', 20, 2)->nullable()->after('population');
            $table->decimal('imports', 20, 2)->nullable()->after('exports');
        });
    }

    public function down(): void
    {
        Schema::table('economic_indicators', function (Blueprint $table) {

            $table->dropColumn([
                'inflation',
                'population',
                'exports',
                'imports'
            ]);

            $table->decimal('inflation_rate', 5, 2)->nullable();
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->decimal('unemployment_rate', 5, 2)->nullable();
        });
    }
};