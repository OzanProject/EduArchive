<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SchoolLevel;
use Illuminate\Http\Request;

class SchoolLevelController extends Controller
{
    public function index()
    {
        $levels = SchoolLevel::orderBy('sequence')->get();
        return view('backend.superadmin.school-levels.index', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:school_levels,name|max:50',
            'description' => 'nullable|string|max:255',
            'sequence' => 'required|integer|min:0',
        ]);

        SchoolLevel::create($request->all());

        return redirect()->back()->with('success', 'Jenjang sekolah berhasil ditambahkan.');
    }

    public function update(Request $request, SchoolLevel $schoolLevel)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:school_levels,name,' . $schoolLevel->id,
            'description' => 'nullable|string|max:255',
            'sequence' => 'required|integer|min:0',
        ]);

        $schoolLevel->update($request->all());

        return redirect()->back()->with('success', 'Jenjang sekolah berhasil diperbarui.');
    }

    public function destroy(SchoolLevel $schoolLevel)
    {
        $schoolLevel->delete();
        return redirect()->back()->with('success', 'Jenjang sekolah berhasil dihapus.');
    }
}
