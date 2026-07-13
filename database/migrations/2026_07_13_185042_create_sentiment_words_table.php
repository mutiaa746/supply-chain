<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah tabel sudah ada
        if (!Schema::hasTable('sentiment_words')) {
            Schema::create('sentiment_words', function (Blueprint $table) {
                $table->id();
                $table->string('word', 50)->unique();
                $table->enum('type', ['positive', 'negative']);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sentiment_words');
    }
};