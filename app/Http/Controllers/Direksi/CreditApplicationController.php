<?php

namespace App\Http\Controllers\Direksi;

use App\Http\Controllers\Controller;
use App\Models\CreditApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; // Untuk menggunakan Gate
use Illuminate\Support\Facades\DB; // Untuk transaksi

class CreditApplicationController extends Controller
{
    // Pastikan user adalah direksi atau admin
    public function __construct()
    {
        $this->middleware('can:access-direksi-features');
    }

    /**
     * Display a listing of the resource (Daftar Semua Pengajuan Kredit untuk Direksi).
     */
    public function index()
    {
        // Direksi melihat semua pengajuan, mungkin dengan filter status
        $applications = CreditApplication::with('customer', 'createdBy') // Eager load customer dan teller yang membuat
                            ->latest()
                            ->paginate(10);

        return view('direksi.applications.index', compact('applications'));
    }

    /**
     * Display the specified resource (Detail Pengajuan Kredit).
     */
    public function show(CreditApplication $creditApplication)
    {
        // Pastikan Direksi memiliki izin untuk melihat detail pengajuan ini
        $this->authorize('view', $creditApplication);

        // Load scoring details dan parameter terkait
        $creditApplication->load('customer', 'createdBy', 'scoringDetails.scoringParameter');

        return view('direksi.applications.show', compact('creditApplication'));
    }

    /**
     * Update the application status to 'approved'.
     */
    public function approve(Request $request, CreditApplication $creditApplication)
    {
        // Pastikan Direksi memiliki izin untuk menyetujui/menolak
        $this->authorize('approve-reject-application', $creditApplication);

        // Hanya bisa menyetujui jika statusnya 'on_review'
        if ($creditApplication->application_status !== 'on_review') {
            return redirect()->back()->with('error', 'Pengajuan ini tidak dapat disetujui. Status saat ini: ' . ucfirst($creditApplication->application_status));
        }

        DB::beginTransaction();
        try {
            $creditApplication->update([
                'application_status' => 'approved',
                // Anda bisa menambahkan kolom 'approved_by' atau 'approved_date' jika diperlukan
            ]);

            // Catat aktivitas
            // ActivityLog::create([
            //     'user_id' => auth()->id(),
            //     'activity' => 'Menyetujui pengajuan kredit #' . $creditApplication->id . '.',
            //     'ip_address' => $request->ip(),
            //     'user_agent' => $request->userAgent(),
            // ]);

            DB::commit();
            return redirect()->route('direksi.applications.index')->with('success', 'Pengajuan berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error approving credit application: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyetujui pengajuan.');
        }
    }

    /**
     * Update the application status to 'rejected' by Direksi.
     */
    public function reject(Request $request, CreditApplication $creditApplication)
    {
        // Pastikan Direksi memiliki izin untuk menyetujui/menolak
        $this->authorize('approve-reject-application', $creditApplication);

        // Validasi alasan penolakan
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        // Hanya bisa menolak jika statusnya 'on_review'
        if ($creditApplication->application_status !== 'on_review') {
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
            return redirect()->route('direksi.applications.index')->with('success', 'Pengajuan berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error rejecting credit application by Direksi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak pengajuan.');
        }
    }
}