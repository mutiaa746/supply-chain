<?php

namespace App\Services;

use App\Models\PositiveWord;
use App\Models\NegativeWord;

class SentimentAnalysisService
{
    public function analyze(string $text): array
    {
        // 1. Ubah menjadi huruf kecil
        $text = strtolower($text);

        // 2. Hapus tanda baca
        $text = preg_replace('/[^a-zA-Z0-9\s]/', '', $text);

        // 3. Tokenisasi
        $words = preg_split('/\s+/', trim($text));

        $positiveScore = 0;
        $negativeScore = 0;

        foreach ($words as $word) {

            if (PositiveWord::where('word', $word)->exists()) {
                $positiveScore++;
            }

            if (NegativeWord::where('word', $word)->exists()) {
                $negativeScore++;
            }
        }

        // Menentukan sentimen
        if ($positiveScore > $negativeScore) {
            $sentiment = 'Positive';
        } elseif ($negativeScore > $positiveScore) {
            $sentiment = 'Negative';
        } else {
            $sentiment = 'Neutral';
        }

        return [
            'positive_score' => $positiveScore,
            'negative_score' => $negativeScore,
            'sentiment' => $sentiment
        ];
    }
}