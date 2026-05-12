<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visitation extends Model
{
    protected $fillable = [
        'qr_code',
        'nomor_antrian',
        'jenis_wbp',
        'tanggal_kunjungan',
        'jenis_identitas',
        'nama_pengunjung',
        'nomor_identitas',
        'no_hp',
        'alamat',
        'nama_wbp',
        'hubungan_wbp',
        'jumlah_pengikut',
        'foto_pegang_identitas',
        'foto_identitas',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'verified_at' => 'datetime',
        'jumlah_pengikut' => 'array',
    ];

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
