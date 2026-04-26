<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['l', 'p']);
            $table->string('pekerjaan');
            $table->string('jenis_layanan');
            $table->enum('frekuensi_penggunaan', ['pertama', 'jarang', 'cukup_sering', 'sangat_sering']);

            $table->tinyInteger('skor_kemudahan_akses');
            $table->tinyInteger('skor_kecepatan_web');
            $table->tinyInteger('skor_kejelasan_informasi');
            $table->tinyInteger('skor_kemudahan_fitur');
            $table->tinyInteger('skor_keandalan_sistem');
            $table->tinyInteger('skor_keamanan_data');
            $table->tinyInteger('skor_kepuasan_keseluruhan');

            $table->text('kendala')->nullable();
            $table->text('saran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
