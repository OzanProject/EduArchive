<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PipData;
use App\Models\Student;
use App\Imports\PipImport;
use Maatwebsite\Excel\Facades\Excel;

class TenantPipController extends Controller
{
    public function index()
    {
        $pipData = PipData::latest()->paginate(15);
        $pageTitle = "Data Usulan PIP";
        return view('backend.adminlembaga.pip.index', compact('pipData', 'pageTitle'));
    }

    public function create()
    {
        $students = Student::whereIn('status_kelulusan', ['Aktif', 'aktif'])->get();
        return view('backend.adminlembaga.pip.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_siswa' => 'required|string',
            'tahun_usulan' => 'required|numeric',
        ]);

        PipData::create([
            'nisn' => $request->nisn,
            'nama_siswa' => $request->nama_siswa,
            'tahun_usulan' => $request->tahun_usulan,
            'tahap' => $request->tahap,
            'nominal' => $request->nominal,
            'pesan_lembaga' => $request->pesan_lembaga,
            'status' => 'usulan_sekolah'
        ]);

        return redirect()->route('adminlembaga.pip.index')->with('success', 'Usulan PIP berhasil ditambahkan.');
    }

    public function template()
    {
        return Excel::download(new \App\Exports\PipTemplateExport, 'Template_Import_PIP.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new PipImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data PIP berhasil diimport dari Excel.');
    }

    public function destroy($id)
    {
        $pip = PipData::findOrFail($id);
        if ($pip->status !== 'usulan_sekolah') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus data yang sudah diproses Dinas.');
        }
        $pip->delete();
        return redirect()->back()->with('success', 'Usulan PIP dibatalkan/dihapus.');
    }
}
