<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVisitationRequest;
use App\Models\Visitation;
use Carbon\Carbon;
use Inertia\Inertia;

use Illuminate\Http\Request;

class VisitationController extends Controller
{
    public function store(StoreVisitationRequest $request)
    {
        $validatedData = $request->validated();

        $pathPegangIdentitas = $request->file('foto_pegang_identitas')->store('visitations', 'public');
        $pathIdentitas = $request->file('foto_identitas')->store('visitations', 'public');

        $tanggalKunjungan = $validatedData['tanggal_kunjungan'];

        $lastQueue = Visitation::whereDate('tanggal_kunjungan', $tanggalKunjungan)->max('nomor_antrian');

        $nomorAntrian = $lastQueue ? $lastQueue + 1 : 1;

        $prefix = $validatedData['jenis_wbp'] === 'tahanan' ? 'T' : 'N';
        $formatTanggal = Carbon::parse($tanggalKunjungan)->format('Ymd');

        $qrCodeString = sprintf('%s-%03d-%s', $prefix, $nomorAntrian, $formatTanggal);

        $data = [
            'qr_code' => $qrCodeString,
            'nomor_antrian' => $nomorAntrian,
            'jenis_wbp' => $validatedData['jenis_wbp'],
            'tanggal_kunjungan' => $tanggalKunjungan,
            'jenis_identitas' => $validatedData['jenis_identitas'],
            'nama_pengunjung' => $validatedData['nama_pengunjung'],
            'nomor_identitas' => $validatedData['nomor_identitas'],
            'no_hp' => $validatedData['no_hp'],
            'alamat' => $validatedData['alamat'],
            'nama_wbp' => $validatedData['nama_wbp'],
            'hubungan_wbp' => $validatedData['hubungan_wbp'],
            'jumlah_pengikut' => $validatedData['jumlah_pengikut'],
            'foto_pegang_identitas' => $pathPegangIdentitas,
            'foto_identitas' => $pathIdentitas,
        ];

        $visitation = Visitation::create($data);

        return redirect()->route('visitation.success', $visitation->qr_code);
    }

    public function success($qr_code)
    {
        $visitation = Visitation::where('qr_code', $qr_code)->firstOrFail();

        return Inertia::render('Public/VisitationSuccess', [
            'visitation' => $visitation
        ]);
    }

    public function checkQuota(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date'
        ]);

        $tanggal = $request->tanggal;

        $terisi = Visitation::whereDate('tanggal_kunjungan', $tanggal)->count();
        $maksimal = 50;
        $sisa = $maksimal - $terisi;

        return response()->json([
            'tanggal' => $tanggal,
            'terisi' => $terisi,
            'sisa_kuota' => max(0, $sisa),
            'is_full' => $sisa <= 0
        ]);
    }
}
