<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    /**
     * Display the main dashboard with metrics, monthly charts, and latest letters.
     */
    public function index(): View
    {
        $totalSuratMasuk = SuratMasuk::count();
        $totalSuratKeluar = SuratKeluar::count();
        $totalUsers = User::count();

        // 5 Surat Masuk Terbaru
        $latestSuratMasuk = SuratMasuk::with('diterimaOleh')
            ->latest('tanggal_terima')
            ->latest('id')
            ->take(5)
            ->get();

        // 5 Surat Keluar Terbaru
        $latestSuratKeluar = SuratKeluar::with('dibuatOleh')
            ->latest('tanggal_surat')
            ->latest('id')
            ->take(5)
            ->get();

        // Data Statistik 6 Bulan Terakhir untuk Chart
        $months = [];
        $dataMasuk = [];
        $dataKeluar = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthLabel = $monthDate->translatedFormat('M Y');
            $year = $monthDate->year;
            $month = $monthDate->month;

            $months[] = $monthLabel;

            $dataMasuk[] = SuratMasuk::whereYear('tanggal_terima', $year)
                ->whereMonth('tanggal_terima', $month)
                ->count();

            $dataKeluar[] = SuratKeluar::whereYear('tanggal_surat', $year)
                ->whereMonth('tanggal_surat', $month)
                ->count();
        }

        $chartMasuk = [
            'labels' => $months,
            'data' => $dataMasuk,
        ];

        $chartKeluar = [
            'labels' => $months,
            'data' => $dataKeluar,
        ];

        return view('dashboard.index', compact(
            'totalSuratMasuk',
            'totalSuratKeluar',
            'totalUsers',
            'latestSuratMasuk',
            'latestSuratKeluar',
            'chartMasuk',
            'chartKeluar'
        ));
    }
}
