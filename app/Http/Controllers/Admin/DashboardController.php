<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitation;
use App\Models\Survey;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $stats = [
            'total_kunjungan' => Visitation::count(),
            'kunjungan_bulan_ini' => Visitation::whereBetween('tanggal_kunjungan', [$startOfMonth, $endOfMonth])->count(),
            'kunjungan_minggu_ini' => Visitation::whereBetween('tanggal_kunjungan', [$startOfWeek, $endOfWeek])->count(),
            'kunjungan_hari_ini' => Visitation::whereDate('tanggal_kunjungan', $today)->count(),
            'kunjungan_besok' => Visitation::whereDate('tanggal_kunjungan', $tomorrow)->count(),
            'total_tahanan' => Visitation::where('jenis_wbp', 'tahanan')->count(),
            'total_narapidana' => Visitation::where('jenis_wbp', 'narapidana')->count(),

            'total_survey' => Survey::count(),
            'survey_hari_ini' => Survey::whereDate('created_at', $today)->count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats
        ]);
    }
}
