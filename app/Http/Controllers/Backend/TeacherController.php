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

    public function store(\App\Http\Requests\StoreTeacherRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('teachers', 'public');
        }

        // Create
        \App\Models\Teacher::create($validated);

        return redirect()->route('adminlembaga.teachers.index')->with('success', 'Data Guru berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $teacher = \App\Models\Teacher::findOrFail($id);
        return view('backend.adminlembaga.teachers.edit', compact('teacher'));
    }

    public function update(\App\Http\Requests\UpdateTeacherRequest $request, string $id)
    {
        $teacher = \App\Models\Teacher::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($teacher->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($teacher->foto);
            }
            $validated['foto'] = $request->file('foto')->store('teachers', 'public');
        }

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
