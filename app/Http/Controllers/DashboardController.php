<?php

namespace App\Http\Controllers;

use App\Models\CreditApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Untuk manipulasi tanggal

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        // Data umum untuk semua dashboard
        $data['total_applications'] = CreditApplication::count();
        $data['approved_applications'] = CreditApplication::where('application_status', 'approved')->count();
        $data['rejected_applications'] = CreditApplication::where('application_status', 'rejected')->count();
        $data['pending_applications'] = CreditApplication::where('application_status', 'pending')->count();
        $data['on_review_applications'] = CreditApplication::where('application_status', 'on_review')->count();

        // Data untuk grafik status pengajuan (pie chart)
        $data['application_status_chart_data'] = [
            'labels' => ['Disetujui', 'Ditolak', 'Menunggu', 'Sedang Direview'],
            'data' => [
                $data['approved_applications'],
                $data['rejected_applications'],
                $data['pending_applications'],
                $data['on_review_applications'],
            ],
            'colors' => ['#4CAF50', '#F44336', '#FFC107', '#2196F3'], // Green, Red, Amber, Blue
        ];

        // Data untuk grafik tren pengajuan bulanan (bar chart)
        $monthlyApplications = CreditApplication::selectRaw('MONTH(application_date) as month, YEAR(application_date) as year, count(*) as total')
                                ->groupBy('month', 'year')
                                ->orderBy('year', 'asc')
                                ->orderBy('month', 'asc')
                                ->get();

        $monthlyLabels = [];
        $monthlyData = [];
        foreach ($monthlyApplications as $item) {
            $monthName = Carbon::createFromDate($item->year, $item->month, 1)->translatedFormat('F Y');
            $monthlyLabels[] = $monthName;
            $monthlyData[] = $item->total;
        }
        $data['monthly_applications_chart_data'] = [
            'labels' => $monthlyLabels,
            'data' => $monthlyData,
        ];


        if ($user->isAdmin()) {
            return view('dashboard', $data); // Admin melihat dashboard umum
        } elseif ($user->isTeller()) {
            // Teller hanya melihat pengajuan yang dia buat
            $data['my_total_applications'] = CreditApplication::where('created_by', $user->id)->count();
            $data['my_approved_applications'] = CreditApplication::where('created_by', $user->id)->where('application_status', 'approved')->count();
            $data['my_rejected_applications'] = CreditApplication::where('created_by', $user->id)->where('application_status', 'rejected')->count();
            $data['my_pending_applications'] = CreditApplication::where('created_by', $user->id)->where('application_status', 'pending')->count();
            $data['my_on_review_applications'] = CreditApplication::where('created_by', $user->id)->where('application_status', 'on_review')->count();

            $data['my_application_status_chart_data'] = [
                'labels' => ['Disetujui', 'Ditolak', 'Menunggu', 'Sedang Direview'],
                'data' => [
                    $data['my_approved_applications'],
                    $data['my_rejected_applications'],
                    $data['my_pending_applications'],
                    $data['my_on_review_applications'],
                ],
                'colors' => ['#4CAF50', '#F44336', '#FFC107', '#2196F3'],
            ];

            return view('teller.dashboard', $data); // Teller melihat dashboard khusus mereka
        } elseif ($user->isKabag()) {
            // Kabag melihat semua pengajuan, tapi mungkin fokus pada yang pending/on_review
            // Data umum sudah cukup, bisa ditambahkan filter jika perlu
            return view('kabag.dashboard', $data); // Kabag melihat dashboard khusus mereka
        } elseif ($user->isDireksi()) {
            // Direksi melihat semua pengajuan, fokus pada keputusan akhir
            return view('direksi.dashboard', $data); // Direksi melihat dashboard khusus mereka
        }

        return view('dashboard', $data); // Default dashboard jika role tidak dikenali
    }
}