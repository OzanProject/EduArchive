<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'Aktif');

        // Strict check to ensure valid status for view/import
        if ($status !== 'Lulus') {
            $status = 'Aktif';
        }

        $query = \App\Models\Student::with('classroom')->latest();

        if ($status == 'Lulus') {
            $query->where('status_kelulusan', 'Lulus');
            $pageTitle = 'Data Siswa Lulusan';
        } else {
            $query->where('status_kelulusan', 'Aktif');
            $pageTitle = 'Data Siswa Aktif';
        }

        $students = $query->paginate(10);
        return view('backend.adminlembaga.students.index', compact('students', 'pageTitle', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classrooms = \App\Models\Classroom::where('is_active', true)->orderBy('nama_kelas')->get();
        return view('backend.adminlembaga.students.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'gender' => 'required|in:L,P', // Added gender validation
            'classroom_id' => 'nullable|exists:classrooms,id',
            'nik' => 'nullable|string',
            'nisn' => 'nullable|string',
            'foto_profil' => 'nullable|image|max:2048',
        ]);

        // Default status for new students
        $validated['status_kelulusan'] = 'Aktif';

        if ($request->hasFile('foto_profil')) {
            $validated['foto_profil'] = $request->file('foto_profil')->store('students', 'public');
        }

        // Add other non-validated but allowed fields
        $validated['birth_place'] = $request->birth_place;
        $validated['birth_date'] = $request->birth_date;
        $validated['address'] = $request->address;
        $validated['parent_name'] = $request->parent_name;
        $validated['year_in'] = $request->year_in;
        // Sync 'kelas' string field for legacy support if needed, or just rely on relationship
        // For now, let's look up classroom name if ID is present
        if ($request->classroom_id) {
            $classroom = \App\Models\Classroom::find($request->classroom_id);
            $validated['kelas'] = $classroom ? $classroom->nama_kelas : null;
        }

        \App\Models\Student::create($validated);

        $prefix = auth()->user()->role === 'operator' ? 'operator.' : 'adminlembaga.';
        return redirect()->route($prefix . 'students.index', ['status' => $validated['status_kelulusan']])->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $classrooms = \App\Models\Classroom::where('is_active', true)->orderBy('nama_kelas')->get();
        return view('backend.adminlembaga.students.edit', compact('student', 'classrooms'));
    }

    public function update(Request $request, string $id)
    {
        $student = \App\Models\Student::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'gender' => 'required|in:L,P', // Added gender validation
            'classroom_id' => 'nullable|exists:classrooms,id',
            'nik' => 'nullable|string',
            'nisn' => 'nullable|string',
            'no_seri_ijazah' => 'nullable|string',
            'status_kelulusan' => 'nullable|in:Aktif,Lulus,Pindah,DO',
            'foto_profil' => 'nullable|image|max:2048',
            'tahun_lulus' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($student->foto_profil) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($student->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')->store('students', 'public');
        }

        // Add other non-validated but allowed fields
        $validated['birth_place'] = $request->birth_place;
        $validated['birth_date'] = $request->birth_date;
        $validated['address'] = $request->address;
        $validated['parent_name'] = $request->parent_name;
        $validated['year_in'] = $request->year_in;

        if ($request->filled('tahun_lulus')) {
            $validated['tahun_lulus'] = $request->tahun_lulus;
        }

        if ($request->classroom_id) {
            $classroom = \App\Models\Classroom::find($request->classroom_id);
            $validated['kelas'] = $classroom ? $classroom->nama_kelas : null;
        }

        $student->update($validated);

        $prefix = auth()->user()->role === 'operator' ? 'operator.' : 'adminlembaga.';
        return redirect()->route($prefix . 'students.index', ['status' => $student->status_kelulusan])->with('success', 'Data Siswa berhasil diperbarui.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'status' => 'required|in:Aktif,Lulus'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\StudentsImport($request->status), $request->file('file'));
            return redirect()->back()->with('success', 'Data siswa berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
    public function destroy(string $id)
    {
        $student = \App\Models\Student::findOrFail($id);

        if ($student->foto_profil) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($student->foto_profil);
        }

        $student->delete();

        $prefix = auth()->user()->role === 'operator' ? 'operator.' : 'adminlembaga.';
        return redirect()->route($prefix . 'students.index', ['status' => $student->status_kelulusan])->with('success', 'Data Siswa berhasil dihapus.');
    }

    public function print(string $id)
    {
        $student = \App\Models\Student::with('classroom')->findOrFail($id);
        return view('backend.adminlembaga.students.print', compact('student'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids) {
            return redirect()->back()->with('error', 'Tidak ada siswa yang dipilih.');
        }

        $students = \App\Models\Student::whereIn('id', $ids)->get();
        foreach ($students as $student) {
            /** @var \App\Models\Student $student */
            if ($student->foto_profil) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($student->foto_profil);
            }
            $student->delete();
        }

        return redirect()->back()->with('success', count($ids) . ' siswa berhasil dihapus.');
    }

    public function bulkPrint(Request $request)
    {
        // IDs can be from query string (GET) or post body (POST)
        // Here we expect GET: ?ids=1,2,3
        $ids = explode(',', $request->query('ids', ''));

        if (empty($ids) || (count($ids) == 1 && empty($ids[0]))) {
            return redirect()->back()->with('error', 'Tidak ada siswa yang dipilih.');
        }

        $students = \App\Models\Student::with('classroom')->whereIn('id', $ids)->get();

        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        return view('backend.adminlembaga.students.print_bulk', compact('students'));
    }

    public function downloadTemplate(Request $request)
    {
        $status = $request->get('status', 'Aktif');

        if ($status == 'Lulus') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GraduatedStudentTemplateExport, 'template_siswa_lulusan.xlsx');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentTemplateExport, 'template_siswa_aktif.xlsx');
    }

    public function bulkPromote(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id',
            'target_classroom_id' => 'required|exists:classrooms,id',
        ]);

        $targetClassroom = \App\Models\Classroom::findOrFail($request->target_classroom_id);

        \App\Models\Student::whereIn('id', $request->ids)->update([
            'classroom_id' => $targetClassroom->id,
            'kelas' => $targetClassroom->nama_kelas, // Sync legacy field
        ]);

        return response()->json(['success' => true, 'message' => count($request->ids) . ' siswa berhasil dinaikkan ke kelas ' . $targetClassroom->nama_kelas]);
    }

    public function bulkGraduate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id',
            'graduation_year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        \App\Models\Student::whereIn('id', $request->ids)->update([
            'status_kelulusan' => 'Lulus',
            'tahun_lulus' => $request->graduation_year,
            // Optional: Detach from classroom or keep as history
            // 'classroom_id' => null, 
        ]);

        return response()->json(['success' => true, 'message' => count($request->ids) . ' siswa berhasil diluluskan.']);

    }
}
