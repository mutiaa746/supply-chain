<?php

namespace App\Services;

class RiskAnalysisService
{
    public function analyze(array $sentiment): array
    {
        $positiveScore = $sentiment['positive_score'];
        $negativeScore = $sentiment['negative_score'];

        // Total kata yang dikenali oleh lexicon
        $totalScore = $positiveScore + $negativeScore;

        // Hitung Risk Score (%)
        if ($totalScore > 0) {
            $riskScore = round(($negativeScore / $totalScore) * 100, 2);
        } else {
            $riskScore = 0;
        }

        // Tentukan Risk Level
        if ($riskScore >= 70) {
            $riskLevel = 'High';
        } elseif ($riskScore >= 40) {
            $riskLevel = 'Medium';
        } else {
            $riskLevel = 'Low';
        }

        return [
            'positive_score' => $positiveScore,
            'negative_score' => $negativeScore,
            'total_score'    => $totalScore,
            'risk_score'     => $riskScore,
            'risk_level'     => $riskLevel,
            'sentiment'      => $sentiment['sentiment'],
        ];
    }
}