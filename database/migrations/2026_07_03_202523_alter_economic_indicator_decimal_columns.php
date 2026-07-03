<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE economic_indicators MODIFY gdp DECIMAL(30,2) NULL");
        DB::statement("ALTER TABLE economic_indicators MODIFY exports DECIMAL(30,2) NULL");
        DB::statement("ALTER TABLE economic_indicators MODIFY imports DECIMAL(30,2) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE economic_indicators MODIFY gdp DECIMAL(20,2) NULL");
        DB::statement("ALTER TABLE economic_indicators MODIFY exports DECIMAL(20,2) NULL");
        DB::statement("ALTER TABLE economic_indicators MODIFY imports DECIMAL(20,2) NULL");
    }
};