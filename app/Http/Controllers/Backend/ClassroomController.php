<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classrooms = \App\Models\Classroom::with('teacher')->latest()->paginate(10);
        return view('backend.adminlembaga.classrooms.index', compact('classrooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = \App\Models\Teacher::where('is_active', true)->orderBy('nama_lengkap')->get();
        return view('backend.adminlembaga.classrooms.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string',
            'tingkat' => 'nullable|string',
            'jurusan' => 'nullable|string',
            'wali_kelas_id' => 'nullable|exists:teachers,id',
        ]);

        \App\Models\Classroom::create($validated);

        return redirect()->route('adminlembaga.classrooms.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $classroom = \App\Models\Classroom::findOrFail($id);
        $teachers = \App\Models\Teacher::where('is_active', true)->orderBy('nama_lengkap')->get();
        return view('backend.adminlembaga.classrooms.edit', compact('classroom', 'teachers'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string',
            'tingkat' => 'nullable|string',
            'jurusan' => 'nullable|string',
            'wali_kelas_id' => 'nullable|exists:teachers,id',
        ]);

        $classroom = \App\Models\Classroom::findOrFail($id);
        $classroom->update($validated);

        return redirect()->route('adminlembaga.classrooms.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $classroom = \App\Models\Classroom::findOrFail($id);
        $classroom->delete();
        return redirect()->route('adminlembaga.classrooms.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
