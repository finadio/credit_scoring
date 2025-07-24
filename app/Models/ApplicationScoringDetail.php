<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationScoringDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_application_id',
        'scoring_parameter_id',
        'input_value',
        'calculated_score',
    ];

    // Relasi
    public function creditApplication()
    {
        return $this->belongsTo(CreditApplication::class);
    }

    public function scoringParameter()
    {
        return $this->belongsTo(ScoringParameter::class);
    }
}