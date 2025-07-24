<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoringParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'parameter_name',
        'category',
        'description',
        'rules', // Jangan lupa ini untuk kolom JSON
    ];

    protected $casts = [
        'rules' => 'array', // Ini akan otomatis mengkonversi JSON ke array PHP
    ];

    public function applicationScoringDetails()
    {
        return $this->hasMany(ApplicationScoringDetail::class);
    }
}