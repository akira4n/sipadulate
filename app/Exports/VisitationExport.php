<?php

namespace App\Exports;

use App\Models\Visitation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VisitationExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Visitation::query()->orderBy('tanggal_kunjungan', 'desc');
    }

    public function headings(): array
    {
        return [
            'No. Antrian',
            'QR Code',
            'Status',
            'Tgl Kunjungan',
            'Jenis WBP',
            'Nama Pengunjung',
            'Jenis Identitas',
            'No. Identitas',
            'No. HP',
            'Alamat',
            'Nama WBP',
            'Hubungan',
            'Jml Pengikut',
            'Diverifikasi Pada'
        ];
    }

    public function map($visitation): array
    {
        return [
            $visitation->nomor_antrian,
            $visitation->qr_code,
            strtoupper($visitation->status),
            $visitation->tanggal_kunjungan->format('d-m-Y'),
            strtoupper($visitation->jenis_wbp),
            $visitation->nama_pengunjung,
            $visitation->jenis_identitas,
            $visitation->nomor_identitas,
            $visitation->no_hp,
            $visitation->alamat,
            $visitation->nama_wbp,
            $visitation->hubungan_wbp,
            $visitation->jumlah_pengikut,
            $visitation->verified_at ? $visitation->verified_at->format('d-m-Y H:i:s') : 'Belum Verifikasi'
        ];
    }
}
