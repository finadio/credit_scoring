<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi (sudah ada)
    public function createdApplications()
    {
        return $this->hasMany(CreditApplication::class, 'created_by');
    }

    public function approvedApplications()
    {
        return $this->hasMany(CreditApplication::class, 'approved_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // --- Helper Methods untuk Role ---
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeller()
    {
        return $this->role === 'teller';
    }

    public function isKabag()
    {
        return $this->role === 'kabag';
    }

    public function isDireksi()
    {
        return $this->role === 'direksi';
    }
}