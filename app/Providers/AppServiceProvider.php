<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate; // Import Gate facade
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User; // Import User model

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy', // Contoh
        // Akan kita tambahkan policy di sini nanti
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define Gates here

        // Gate untuk mengecek apakah user adalah Admin
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        // Gate untuk akses Teller
        Gate::define('access-teller-features', function (User $user) {
            return $user->isTeller() || $user->isAdmin(); // Admin juga bisa akses fitur teller
        });

        // Gate untuk akses Kabag
        Gate::define('access-kabag-features', function (User $user) {
            return $user->isKabag() || $user->isAdmin(); // Admin juga bisa akses fitur kabag
        });

        // Gate untuk akses Direksi
        Gate::define('access-direksi-features', function (User $user) {
            return $user->isDireksi() || $user->isAdmin(); // Admin juga bisa akses fitur direksi
        });

        // Gate untuk melihat skor detail (hanya Kabag dan Direksi)
        Gate::define('view-scoring-details', function (User $user) {
            return $user->isKabag() || $user->isDireksi() || $user->isAdmin();
        });

        // Gate untuk menyetujui/menolak pengajuan (hanya Direksi)
        Gate::define('approve-reject-application', function (User $user) {
            return $user->isDireksi() || $user->isAdmin();
        });
    }
}