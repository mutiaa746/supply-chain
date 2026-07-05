<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SentimentWordsSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel
        DB::table('positive_words')->truncate();
        DB::table('negative_words')->truncate();

        // Lokasi file
        $positiveFile = storage_path('app/lexicon/positive-words.txt');
        $negativeFile = storage_path('app/lexicon/negative-words.txt');

        if (!file_exists($positiveFile) || !file_exists($negativeFile)) {
            $this->command->error('File lexicon tidak ditemukan!');
            return;
        }

        // Baca file
        $positiveWords = file($positiveFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $negativeWords = file($negativeFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $positiveData = [];
        $negativeData = [];

        // ==========================
        // POSITIVE WORDS
        // ==========================
        foreach ($positiveWords as $word) {

            $word = trim($word);

            // Hilangkan BOM
            $word = preg_replace('/^\xEF\xBB\xBF/', '', $word);

            $word = strtolower($word);

            if ($word == '' || str_starts_with($word, ';')) {
                continue;
            }

            $positiveData[] = $word;
        }

        // ==========================
        // NEGATIVE WORDS
        // ==========================
        foreach ($negativeWords as $word) {

            $word = trim($word);

            // Hilangkan BOM
            $word = preg_replace('/^\xEF\xBB\xBF/', '', $word);

            $word = strtolower($word);

            if ($word == '' || str_starts_with($word, ';')) {
                continue;
            }

            $negativeData[] = $word;
        }

        // Hilangkan duplicate
        $positiveData = array_unique($positiveData);
        $negativeData = array_unique($negativeData);

        // Susun data insert
        $positiveInsert = [];
        foreach ($positiveData as $word) {
            $positiveInsert[] = [
                'word' => $word,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $negativeInsert = [];
        foreach ($negativeData as $word) {
            $negativeInsert[] = [
                'word' => $word,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert per chunk
        foreach (array_chunk($positiveInsert, 500) as $chunk) {
            DB::table('positive_words')->insertOrIgnore($chunk);
        }

        foreach (array_chunk($negativeInsert, 500) as $chunk) {
            DB::table('negative_words')->insertOrIgnore($chunk);
        }

        $this->command->info('===================================');
        $this->command->info('Lexicon berhasil diimport!');
        $this->command->info('Positive : ' . count($positiveInsert));
        $this->command->info('Negative : ' . count($negativeInsert));
        $this->command->info('===================================');
    }
}