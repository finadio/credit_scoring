<?php

namespace App\Services;

use App\Models\CreditApplication;
use App\Models\ScoringParameter;
use App\Models\ApplicationScoringDetail;

class CreditScoringService
{
    /**
     * Calculates the credit score for an application and saves the details.
     *
     * @param CreditApplication $application The credit application instance.
     * @param string $category The category of the application (UMKM/Pengusaha or Pegawai).
     * @param array $scoringInputs Array of inputs from the teller, format: [['parameter_id' => id, 'value' => 'input_value'], ...]
     * @return int The final calculated score.
     */
    public function calculateAndSaveScores(CreditApplication $application, string $category, array $scoringInputs): int
    {
        $finalScore = 0;
        $parameterMap = ScoringParameter::where('category', $category)->get()->keyBy('id');

        foreach ($scoringInputs as $input) {
            $parameterId = $input['parameter_id'];
            $inputValue = $input['value'];

            // Skip if parameter not found or not in the correct category
            if (!isset($parameterMap[$parameterId])) {
                continue;
            }

            $parameter = $parameterMap[$parameterId];
            $calculatedScore = 0; // Score for this specific parameter

            // Apply scoring rules based on parameter type
            if ($parameter->rules && isset($parameter->rules['type']) && isset($parameter->rules['options'])) {
                if ($parameter->rules['type'] === 'discrete') {
                    foreach ($parameter->rules['options'] as $option) {
                        if (isset($option['value']) && (string)$option['value'] === (string)$inputValue) {
                            $calculatedScore = (int)($option['score'] ?? 0);
                            break;
                        }
                    }
                } elseif ($parameter->rules['type'] === 'range') {
                    $numericValue = (float)$inputValue; // Konversi input ke numerik
                    foreach ($parameter->rules['options'] as $option) {
                        $min = $option['min'] ?? null;
                        $max = $option['max'] ?? null;
                        $score = (int)($option['score'] ?? 0);

                        // Cek jika numericValue berada dalam rentang
                        if (($min === null || $numericValue >= $min) &&
                            ($max === null || $numericValue <= $max)) {
                            $calculatedScore = $score;
                            break;
                        }
                    }
                }
            }

            $finalScore += $calculatedScore;

            // Save individual parameter score detail
            ApplicationScoringDetail::create([
                'credit_application_id' => $application->id,
                'scoring_parameter_id' => $parameterId,
                'input_value' => $inputValue,
                'calculated_score' => $calculatedScore,
            ]);
        }

        return $finalScore;
    }
}