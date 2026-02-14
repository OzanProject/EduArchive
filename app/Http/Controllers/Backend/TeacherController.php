<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = \App\Models\Teacher::latest()->paginate(10);
        return view('backend.adminlembaga.teachers.index', compact('teachers'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\TeachersImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data guru berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TeacherTemplateExport, 'template_guru.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.adminlembaga.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|unique:teachers,nip',
            'nuptk' => 'nullable|string|unique:teachers,nuptk',
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'nullable|email',
            'status_kepegawaian' => 'required',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('teachers', 'public');
        }

        // Add other non-validated but allowed fields
        $validated['gelar_depan'] = $request->gelar_depan;
        $validated['gelar_belakang'] = $request->gelar_belakang;
        $validated['tempat_lahir'] = $request->tempat_lahir;
        $validated['tanggal_lahir'] = $request->tanggal_lahir;
        $validated['alamat'] = $request->alamat;
        $validated['no_hp'] = $request->no_hp;

        // Create
        \App\Models\Teacher::create($validated);

        return redirect()->route('adminlembaga.teachers.index')->with('success', 'Data Guru berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $teacher = \App\Models\Teacher::findOrFail($id);
        return view('backend.adminlembaga.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, string $id)
    {
        $teacher = \App\Models\Teacher::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|unique:teachers,nip,' . $id,
            'nuptk' => 'nullable|string|unique:teachers,nuptk,' . $id,
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'nullable|email',
            'status_kepegawaian' => 'required',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($teacher->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($teacher->foto);
            }
            $validated['foto'] = $request->file('foto')->store('teachers', 'public');
        }

        // Add other non-validated but allowed fields
        $validated['gelar_depan'] = $request->gelar_depan;
        $validated['gelar_belakang'] = $request->gelar_belakang;
        $validated['tempat_lahir'] = $request->tempat_lahir;
        $validated['tanggal_lahir'] = $request->tanggal_lahir;
        $validated['alamat'] = $request->alamat;
        $validated['no_hp'] = $request->no_hp;

        // Update
        $teacher->update($validated);

        return redirect()->route('adminlembaga.teachers.index')->with('success', 'Data Guru berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $teacher = \App\Models\Teacher::findOrFail($id);
        if ($teacher->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($teacher->foto);
        }
        $teacher->delete();
        return redirect()->route('adminlembaga.teachers.index')->with('success', 'Data Guru berhasil dihapus.');
    }
}
