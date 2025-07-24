<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <-- Pastikan ini ada


class CreditApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'application_date',
        'loan_amount',
        'tenor_months',
        'application_status',
        'final_score',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'created_by',
    ];

    protected $casts = [
        'application_date' => 'date',
        'approved_at' => 'datetime',
    ];

    // Relasi
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scoringDetails()
    {
        return $this->hasMany(ApplicationScoringDetail::class);
    }
}