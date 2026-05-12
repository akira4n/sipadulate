<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScanController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Scan/Index');
    }

    public function checkData($qr_code)
    {
        $visitation = Visitation::where('qr_code', $qr_code)->first();

        if (!$visitation) {
            return response()->json(['message' => 'QR Code tidak valid atau data tidak ditemukan!'], 404);
        }

        return response()->json([
            'message' => 'Data ditemukan',
            'data' => $visitation
        ]);
    }

    public function verify(Visitation $visitation)
    {
        if ($visitation->status === 'verified') {
            return response()->json(['message' => 'Kunjungan ini sudah diverifikasi sebelumnya.'], 400);
        }

        $visitation->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Kunjungan berhasil diverifikasi.']);
    }
}
