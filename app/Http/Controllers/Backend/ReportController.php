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
                'active' => Student::whereIn('status_kelulusan', ['Aktif', 'aktif'])->count(),
                'graduated' => Student::whereIn('status_kelulusan', ['Lulus', 'lulus'])->count(),
                'others' => Student::whereNotIn('status_kelulusan', ['Aktif', 'aktif', 'Lulus', 'lulus'])->count(),
            ],
            'teachers' => [
                'total' => Teacher::count(),
                'pns' => Teacher::whereIn('status_kepegawaian', ['PNS', 'pns'])->count(),
                'pppk' => Teacher::whereIn('status_kepegawaian', ['PPPK', 'pppk'])->count(),
                'honorer' => Teacher::whereIn('status_kepegawaian', ['Honorer', 'honorer'])->count(),
            ],
            'classrooms' => Classroom::count(),
            'documents' => Document::count(),
            'school_documents' => SchoolDocument::count(),
        ];

        return view('backend.adminlembaga.reports.index', compact('stats'));
    }
}
