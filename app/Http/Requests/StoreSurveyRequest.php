<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyRequest extends FormRequest
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
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:l,p',
            'pekerjaan' => 'required|string|max:255',
            'jenis_layanan' => 'required|string|max:255',
            'frekuensi_penggunaan' => 'required|in:pertama,jarang,cukup_sering,sangat_sering',
            'skor_kemudahan_akses' => 'required|integer|between:1,5',
            'skor_kecepatan_web' => 'required|integer|between:1,5',
            'skor_kejelasan_informasi' => 'required|integer|between:1,5',
            'skor_kemudahan_fitur' => 'required|integer|between:1,5',
            'skor_keandalan_sistem' => 'required|integer|between:1,5',
            'skor_keamanan_data' => 'required|integer|between:1,5',
            'skor_kepuasan_keseluruhan' => 'required|integer|between:1,5',
            'kendala' => 'nullable|string',
            'saran' => 'nullable|string',
        ];
    }
}
