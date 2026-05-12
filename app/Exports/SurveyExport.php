<?php

namespace App\Exports;

use App\Models\Survey;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SurveyExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Survey::query()->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama',
            'L/P',
            'Pekerjaan',
            'Layanan',
            'Frekuensi',
            'Akses (1-5)',
            'Kecepatan (1-5)',
            'Informasi (1-5)',
            'Fitur (1-5)',
            'Keandalan (1-5)',
            'Keamanan (1-5)',
            'Kepuasan Total (1-5)',
            'Kendala',
            'Saran'
        ];
    }

    public function map($survey): array
    {
        return [
            $survey->created_at->format('d-m-Y H:i'),
            $survey->nama,
            strtoupper($survey->jenis_kelamin),
            $survey->pekerjaan,
            $survey->jenis_layanan,
            str_replace('_', ' ', strtoupper($survey->frekuensi_penggunaan)),
            $survey->skor_kemudahan_akses,
            $survey->skor_kecepatan_web,
            $survey->skor_kejelasan_informasi,
            $survey->skor_kemudahan_fitur,
            $survey->skor_keandalan_sistem,
            $survey->skor_keamanan_data,
            $survey->skor_kepuasan_keseluruhan,
            $survey->kendala,
            $survey->saran
        ];
    }
}
