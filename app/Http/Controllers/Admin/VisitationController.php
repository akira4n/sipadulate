<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Exports\VisitationExport;
use Maatwebsite\Excel\Facades\Excel;

class VisitationController extends Controller
{
    public function index(Request $request)
    {
        $query = Visitation::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pengunjung', 'like', "%{$search}%")
                    ->orWhere('nomor_identitas', 'like', "%{$search}%")
                    ->orWhere('qr_code', 'like', "%{$search}%")
                    ->orWhere('nama_wbp', 'like', "%{$search}%");
            });
        }

        $sortField = $request->input('sort', 'tanggal_kunjungan');
        $sortDir = $request->input('dir', 'desc');

        $allowedSorts = ['tanggal_kunjungan', 'nama_pengunjung', 'status', 'created_at', 'nomor_antrian'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir);
        }

        $perPage = $request->input('per_page', 10);

        $visitations = $query->paginate($perPage)->withQueryString();

        return Inertia::render('Admin/Visitations/Index', [
            'visitations' => $visitations,
            'filters' => $request->only(['search', 'sort', 'dir', 'per_page'])
        ]);
    }

    public function updateStatus(Request $request, Visitation $visitation)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,rejected'
        ]);

        $visitation->update([
            'status' => $request->status,
            'verified_at' => $request->status === 'verified' ? now() : null,
            'verified_by' => $request->status === 'verified' ? auth()->id() : null,
        ]);

        return back()->with('success', 'Status kunjungan berhasil diperbarui.');
    }

    public function export()
    {
        return Excel::download(new VisitationExport, 'data_kunjungan.xlsx');
    }
}
