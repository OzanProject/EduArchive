<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Document;
use App\Models\SchoolDocument;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'students' => [
                'total' => Student::count(),
                'active' => Student::where('status_kelulusan', 'Aktif')->count(),
                'graduated' => Student::where('status_kelulusan', 'Lulus')->count(),
                'others' => Student::whereNotIn('status_kelulusan', ['Aktif', 'Lulus'])->count(),
            ],
            'teachers' => [
                'total' => Teacher::count(),
                'pns' => Teacher::where('status_kepegawaian', 'PNS')->count(),
                'pppk' => Teacher::where('status_kepegawaian', 'PPPK')->count(),
                'honorer' => Teacher::where('status_kepegawaian', 'Honorer')->count(),
            ],
            'classrooms' => Classroom::count(),
            'documents' => Document::count(),
            'school_documents' => SchoolDocument::count(),
        ];

        return view('backend.adminlembaga.reports.index', compact('stats'));
    }
}
