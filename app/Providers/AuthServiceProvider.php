<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User; // Pastikan ini ada: Mengimpor model User
use App\Models\CreditApplication; // Pastikan ini ada: Mengimpor model CreditApplication
use App\Policies\CreditApplicationPolicy; // Pastikan ini ada: Mengimpor policy CreditApplicationPolicy

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Pastikan baris ini ada dan tidak dikomentari
        CreditApplication::class => CreditApplicationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // ===============================================
        //  DEFINISI GATES
        // ===============================================

        // Gate: manage-users
        // Mengizinkan user dengan role 'admin' untuk mengakses fitur manajemen pengguna
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        // Gate: access-teller-features
        // Mengizinkan user dengan role 'teller' DAN 'admin' untuk mengakses fitur teller
        Gate::define('access-teller-features', function (User $user) {
            return $user->isTeller() || $user->isAdmin();
        });

        // Gate: access-kabag-features
        // Mengizinkan user dengan role 'kabag' DAN 'admin' untuk mengakses fitur kabag
        Gate::define('access-kabag-features', function (User $user) {
            return $user->isKabag() || $user->isAdmin();
        });

        // Gate: access-direksi-features
        // Mengizinkan user dengan role 'direksi' DAN 'admin' untuk mengakses fitur direksi
        Gate::define('access-direksi-features', function (User $user) {
            return $user->isDireksi() || $user->isAdmin();
        });

        // Gate: view-scoring-details
        // Mengizinkan user dengan role 'kabag', 'direksi', DAN 'admin' untuk melihat detail skor
        Gate::define('view-scoring-details', function (User $user) {
            return $user->isKabag() || $user->isDireksi() || $user->isAdmin();
        });

        // Gate: approve-reject-application
        // Mengizinkan user dengan role 'direksi' DAN 'admin' untuk menyetujui/menolak pengajuan
        Gate::define('approve-reject-application', function (User $user) {
            return $user->isDireksi() || $user->isAdmin();
        });

        // ===============================================
        //  END DEFINISI GATES
        // ===============================================
    }
}