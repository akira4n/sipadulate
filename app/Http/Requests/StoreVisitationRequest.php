<?php

namespace App\Http\Requests;

use App\Models\Visitation;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreVisitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jenis_wbp' => 'required|in:tahanan,narapidana',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'nama_pengunjung' => 'required|string|max:255',
            'jenis_identitas' => 'required|in:nik,sim,passport,nisn,lainnya',
            'nomor_identitas' => 'required|string|max:50',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'nama_wbp' => 'required|string|max:255',
            'hubungan_wbp' => 'required|in:orang tua,anak,suami,istri,saudara,keponakan,teman,pengacara,lainnya',
            'jumlah_pengikut' => 'required|array|min:1',
            'jumlah_pengikut.*' => 'required|string|max:255',
            'foto_pegang_identitas' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_identitas' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $jenisWbp = $this->input('jenis_wbp');
                $tanggalKunjungan = $this->input('tanggal_kunjungan');
                $nomorIdentitas = $this->input('nomor_identitas');

                if ($jenisWbp && $tanggalKunjungan && $nomorIdentitas) {
                    $hari = Carbon::parse($tanggalKunjungan)->dayOfWeek;

                    if ($jenisWbp === 'tahanan' && !in_array($hari, [1, 5])) {
                        $validator->errors()->add(
                            'tanggal_kunjungan',
                            'Jadwal kunjungan Tahanan hanya tersedia pada hari Senin dan Jumat.'
                        );
                    }

                    if ($jenisWbp === 'narapidana' && !in_array($hari, [2, 3, 4, 6])) {
                        $validator->errors()->add(
                            'tanggal_kunjungan',
                            'Jadwal kunjungan Narapidana hanya tersedia pada hari Selasa, Rabu, Kamis, dan Sabtu.'
                        );
                    }

                    if ($hari === 0) {
                        $validator->errors()->add(
                            'tanggal_kunjungan',
                            'Tidak ada layanan kunjungan pada hari Minggu.'
                        );
                    }

                    $sudahDaftar = Visitation::where('nomor_identitas', $nomorIdentitas)
                        ->whereDate('tanggal_kunjungan', $tanggalKunjungan)
                        ->exists();

                    if ($sudahDaftar) {
                        $validator->errors()->add(
                            'nomor_identitas',
                            'Nomor identitas ini sudah terdaftar untuk kunjungan pada tanggal tersebut. Silakan pilih tanggal lain.'
                        );
                    }

                    $kuotaTerisi = Visitation::whereDate('tanggal_kunjungan', $tanggalKunjungan)->count();

                    if ($kuotaTerisi >= 50) {
                        $validator->errors()->add(
                            'tanggal_kunjungan',
                            'Mohon maaf, kuota kunjungan (50 pendaftar) untuk tanggal ini sudah penuh.'
                        );
                    }
                }
            }
        ];
    }
}
