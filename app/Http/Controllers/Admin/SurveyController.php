<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Exports\SurveyExport;
use Maatwebsite\Excel\Facades\Excel;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $query = Survey::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('pekerjaan', 'like', "%{$search}%")
                    ->orWhere('jenis_layanan', 'like', "%{$search}%");
            });
        }

        $sortField = $request->input('sort', 'created_at');
        $sortDir = $request->input('dir', 'desc');

        $allowedSorts = ['nama', 'pekerjaan', 'skor_kepuasan_keseluruhan', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir);
        }

        $perPage = $request->input('per_page', 10);
        $surveys = $query->paginate($perPage)->withQueryString();

        return Inertia::render('Admin/Surveys/Index', [
            'surveys' => $surveys,
            'filters' => $request->only(['search', 'sort', 'dir', 'per_page'])
        ]);
    }

    public function export()
    {
        return Excel::download(new SurveyExport, 'data_survey.xlsx');
    }
}
