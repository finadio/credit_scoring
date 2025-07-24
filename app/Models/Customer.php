<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nik',
        'phone_number',
        'address',
    ];

    public function creditApplications()
    {
        return $this->hasMany(CreditApplication::class);
    }
}