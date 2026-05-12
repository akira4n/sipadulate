<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyRequest;
use App\Models\Survey;

use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function store(StoreSurveyRequest $request)
    {
        Survey::create($request->validated());

        return redirect()->back()->with('success', 'Terima kasih atas penilaian dan masukan Anda!');
    }
}
