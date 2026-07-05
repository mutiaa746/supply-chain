<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ports', function (Blueprint $table) {

            if (!Schema::hasColumn('ports', 'country_name')) {
                $table->string('country_name')->nullable()->after('port_name');
            }

            if (!Schema::hasColumn('ports', 'harbor_size')) {
                $table->string('harbor_size')->nullable()->after('longitude');
            }

            if (!Schema::hasColumn('ports', 'harbor_type')) {
                $table->string('harbor_type')->nullable()->after('harbor_size');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ports', function (Blueprint $table) {

            if (Schema::hasColumn('ports', 'country_name')) {
                $table->dropColumn('country_name');
            }

            if (Schema::hasColumn('ports', 'harbor_size')) {
                $table->dropColumn('harbor_size');
            }

            if (Schema::hasColumn('ports', 'harbor_type')) {
                $table->dropColumn('harbor_type');
            }
        });
    }
};