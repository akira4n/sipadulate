<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'skor_kemudahan_akses' => 'integer',
        'skor_kecepatan_web' => 'integer',
        'skor_kejelasan_informasi' => 'integer',
        'skor_kemudahan_fitur' => 'integer',
        'skor_keandalan_sistem' => 'integer',
        'skor_keamanan_data' => 'integer',
        'skor_kepuasan_keseluruhan' => 'integer',
    ];
}
