<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    // Default Laravel akan mencari tabel 'activity_logs'.
    // Karena kita tidak punya updated_at, kita perlu memberitahu Laravel.
    public $timestamps = false; // Karena hanya ada created_at

    protected $fillable = [
        'user_id',
        'activity',
        'ip_address',
        'user_agent',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}