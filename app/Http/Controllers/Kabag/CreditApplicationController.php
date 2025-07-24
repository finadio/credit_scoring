<?php

namespace App\Http\Controllers\Kabag;

use App\Http\Controllers\Controller;
use App\Models\CreditApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; // Untuk menggunakan Gate
use Illuminate\Support\Facades\DB; // Untuk transaksi

class CreditApplicationController extends Controller
{
    // Pastikan user adalah kabag atau admin
    public function __construct()
    {
        $this->middleware('can:access-kabag-features');
    }

    /**
     * Display a listing of the resource (Daftar Semua Pengajuan Kredit untuk Kabag).
     */
    public function index()
    {
        // Kabag melihat semua pengajuan, mungkin dengan filter status
        $applications = CreditApplication::with('customer', 'createdBy') // Eager load customer dan teller yang membuat
                            ->latest()
                            ->paginate(10);

        return view('kabag.applications.index', compact('applications'));
    }

    /**
     * Display the specified resource (Detail Pengajuan Kredit).
     */
    public function show(CreditApplication $creditApplication)
    {
        // Pastikan Kabag memiliki izin untuk melihat detail pengajuan ini
        // Menggunakan policy yang sudah didefinisikan
        $this->authorize('view', $creditApplication);

        // Load scoring details dan parameter terkait
        $creditApplication->load('customer', 'createdBy', 'scoringDetails.scoringParameter');

        return view('kabag.applications.show', compact('creditApplication'));
    }

    /**
     * Update the application status to 'on_review' (Meneruskan ke Direksi).
     */
    public function review(Request $request, CreditApplication $creditApplication)
    {
        // Pastikan Kabag memiliki izin untuk mengupdate status
        $this->authorize('update', $creditApplication);

        // Hanya bisa mereview jika statusnya pending
        if ($creditApplication->application_status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan ini tidak dapat direview. Status saat ini: ' . ucfirst($creditApplication->application_status));
        }

        DB::beginTransaction();
        try {
            $creditApplication->update([
                'application_status' => 'on_review',
            ]);

            // Catat aktivitas
            // ActivityLog::create([
            //     'user_id' => auth()->id(),
            //     'activity' => 'Meneruskan pengajuan kredit #' . $creditApplication->id . ' ke Direksi.',
            //     'ip_address' => $request->ip(),
            //     'user_agent' => $request->userAgent(),
            // ]);

            DB::commit();
            return redirect()->route('kabag.applications.index')->with('success', 'Pengajuan berhasil diteruskan ke Direksi.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error reviewing credit application: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mereview pengajuan.');
        }
    }

    /**
     * Update the application status to 'rejected' by Kabag.
     */
    public function reject(Request $request, CreditApplication $creditApplication)
    {
        // Pastikan Kabag memiliki izin untuk mengupdate status
        $this->authorize('update', $creditApplication);

        // Validasi alasan penolakan
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        // Hanya bisa menolak jika statusnya pending atau on_review
        if (!in_array($creditApplication->application_status, ['pending', 'on_review'])) {
            return redirect()->back()->with('error', 'Pengajuan ini tidak dapat ditolak. Status saat ini: ' . ucfirst($creditApplication->application_status));
        }

        DB::beginTransaction();
        try {
            $creditApplication->update([
                'application_status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Catat aktivitas
            // ActivityLog::create([
            //     'user_id' => auth()->id(),
            //     'activity' => 'Menolak pengajuan kredit #' . $creditApplication->id . '. Alasan: ' . $request->rejection_reason,
            //     'ip_address' => $request->ip(),
            //     'user_agent' => $request->userAgent(),
            // ]);

            DB::commit();
            return redirect()->route('kabag.applications.index')->with('success', 'Pengajuan berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error rejecting credit application by Kabag: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak pengajuan.');
        }
    }
}