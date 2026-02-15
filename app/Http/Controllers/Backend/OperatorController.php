<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function index()
    {
        $total_siswa = \App\Models\Student::where('status_kelulusan', 'Aktif')->count();
        $total_dokumen = \App\Models\Document::count();

        return view('backend.operator.dashboard', compact('total_siswa', 'total_dokumen'));
    }
}
