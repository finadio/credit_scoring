<?php

namespace App\Policies;

use App\Models\CreditApplication;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CreditApplicationPolicy
{
    /**
     * Izinkan admin melakukan apa saja.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true; // Admin bisa melakukan semua aksi
        }
        return null; // Lanjutkan ke method policy spesifik
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Semua role bisa melihat daftar pengajuan mereka sendiri atau yang relevan
        return $user->isTeller() || $user->isKabag() || $user->isDireksi();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CreditApplication $creditApplication): bool
    {
        // Teller bisa melihat pengajuan yang dia buat
        if ($user->isTeller() && $creditApplication->created_by === $user->id) {
            return true;
        }

        // Kabag bisa melihat semua pengajuan
        if ($user->isKabag()) {
            return true;
        }

        // Direksi bisa melihat semua pengajuan
        if ($user->isDireksi()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Hanya Teller yang bisa membuat pengajuan baru
        return $user->isTeller();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CreditApplication $creditApplication): bool
    {
        // Hanya Kabag yang bisa mengubah status ke 'on_review' atau 'rejected' (sebelum disetujui Direksi)
        // Direksi juga bisa mengubah status (approved/rejected)
        if ($user->isKabag() && in_array($creditApplication->application_status, ['pending', 'on_review'])) {
            return true;
        }
        if ($user->isDireksi() && $creditApplication->application_status === 'on_review') {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can approve/reject the model (spesifik untuk direksi).
     */
    public function approveReject(User $user, CreditApplication $creditApplication): bool
    {
        // Hanya Direksi yang bisa menyetujui/menolak dan pengajuan harus dalam status 'on_review'
        return $user->isDireksi() && $creditApplication->application_status === 'on_review';
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CreditApplication $creditApplication): bool
    {
        // Umumnya, pengajuan kredit tidak boleh dihapus setelah dibuat.
        // Jika perlu, hanya Admin yang bisa.
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CreditApplication $creditApplication): bool
    {
        // Hanya admin
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CreditApplication $creditApplication): bool
    {
        // Hanya admin
        return $user->isAdmin();
    }
}