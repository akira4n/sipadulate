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
        Schema::create('visitations', function (Blueprint $table) {
            $table->id();
            $table->string('qr_code')->unique();
            $table->integer('nomor_antrian');
            $table->enum('jenis_wbp', ['tahanan', 'narapidana']);
            $table->date('tanggal_kunjungan');
            $table->enum('jenis_identitas', ['nik', 'sim', 'passport', 'nisn', 'lainnya']);
            $table->string('nama_pengunjung');
            $table->string('nomor_identitas');
            $table->string('no_hp');
            $table->string('alamat');
            $table->string('nama_wbp');
            $table->string('hubungan_wbp');
            $table->integer('jumlah_pengikut');

            // file path untuk foto
            $table->string('foto_pegang_identitas');
            $table->string('foto_identitas');

            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitations');
    }
};
