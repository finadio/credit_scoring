<?php

namespace App\Http\Controllers\Teller;

use App\Http\Controllers\Controller;
use App\Models\CreditApplication;
use App\Models\Customer;
use App\Models\ScoringParameter; // Untuk mengambil parameter scoring
use App\Services\CreditScoringService; // Akan kita buat di langkah selanjutnya
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Untuk transaksi database

class CreditApplicationController extends Controller
{
    // Pastikan user adalah teller atau admin
    public function __construct()
    {
        $this->middleware('can:access-teller-features');
    }

    /**
     * Display a listing of the resource (Daftar Pengajuan yang dibuat oleh Teller ini).
     */
    public function index()
    {
        // Hanya tampilkan pengajuan yang dibuat oleh teller yang sedang login
        $applications = CreditApplication::where('created_by', auth()->id())
                            ->with('customer') // Eager load customer data
                            ->latest()
                            ->paginate(10); // Paginate untuk performa

        return view('teller.applications.index', compact('applications'));
    }


    /**
     * Show the form for creating a new resource (Form Pengajuan Kredit Baru).
     */
    public function create()
    {
        $categories = ['UMKM/Pengusaha', 'Pegawai'];
        // Ambil semua parameter scoring untuk ditampilkan di form (berdasarkan kategori)
        $parameters = ScoringParameter::all()->groupBy('category');

        return view('teller.applications.create', compact('categories', 'parameters'));
    }

    /**
     * Store a newly created resource in storage (Memproses Pengajuan Kredit).
     */
    public function store(Request $request, CreditScoringService $scoringService)
    {
        // Validasi data nasabah
        $customerData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_nik' => 'required|string|digits:16|unique:customers,nik', // NIK harus unik
            'customer_phone_number' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
        ]);

        // Validasi data pengajuan kredit
        $applicationData = $request->validate([
            'loan_amount' => 'required|numeric|min:1000000',
            'tenor_months' => 'required|integer|min:1|max:60', // Max tenor 5 tahun (60 bulan)
            'application_category' => ['required', Rule::in(['UMKM/Pengusaha', 'Pegawai'])],
            // Validasi untuk parameter dinamis akan dilakukan di CreditScoringService
        ]);

        // Validasi untuk parameter scoring dinamis (ini adalah contoh sederhana)
        // Anda mungkin perlu validasi lebih detail berdasarkan type rules (range/discrete)
        $request->validate([
            'scoring_inputs' => 'required|array',
            'scoring_inputs.*.parameter_id' => 'required|exists:scoring_parameters,id',
            'scoring_inputs.*.value' => 'nullable|string', // Nilai input dari teller
        ]);


        DB::beginTransaction(); // Memulai transaksi database

        try {
            // 1. Buat Nasabah Baru
            $customer = Customer::create([
                'name' => $customerData['customer_name'],
                'nik' => $customerData['customer_nik'],
                'phone_number' => $customerData['customer_phone_number'],
                'address' => $customerData['customer_address'],
            ]);

            // 2. Buat Pengajuan Kredit
            $application = CreditApplication::create([
                'customer_id' => $customer->id,
                'application_date' => now(),
                'loan_amount' => $applicationData['loan_amount'],
                'tenor_months' => $applicationData['tenor_months'],
                'application_status' => 'pending', // Default status
                'created_by' => auth()->id(),
            ]);

            // 3. Hitung dan Simpan Skor Kredit (Menggunakan Service)
            // CreditScoringService akan menghitung final_score dan menyimpan ApplicationScoringDetail
            $finalScore = $scoringService->calculateAndSaveScores(
                $application,
                $request->input('application_category'),
                $request->input('scoring_inputs')
            );

            // Tentukan status awal berdasarkan skor (ambang batas)
            // Ini ambang batas sementara, bisa jadi configurable di Admin nantinya
            $application->final_score = $finalScore;
            $application->application_status = ($finalScore >= 50) ? 'pending' : 'rejected'; // Jika skor di bawah 50 langsung tolak
            $application->save();

            DB::commit(); // Commit transaksi jika semua berhasil

            // Tentukan pesan untuk Teller
            $message = 'Pengajuan kredit berhasil diajukan.';
            if ($application->application_status === 'rejected') {
                $message .= ' Namun, pengajuan ini langsung ditolak karena skor kredit terlalu rendah.';
                return redirect()->route('teller.applications.index')->with('error', $message);
            } else {
                $message .= ' Menunggu review dari Kepala Bagian.';
                return redirect()->route('teller.applications.index')->with('success', $message);
            }

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            // Log the error for debugging
            \Log::error('Error storing credit application: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan pengajuan: ' . $e->getMessage());
        }
    }
}