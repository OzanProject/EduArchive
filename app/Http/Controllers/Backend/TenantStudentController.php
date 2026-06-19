<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Support\Facades\DB;

class TenantStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'Aktif');

        // Strict check to ensure valid status for view/import
        if ($status !== 'Lulus') {
            $status = 'Aktif';
        }

        $query = \App\Models\Student::with('classroom')->latest();

        if ($status == 'Lulus') {
            $query->where(['status_kelulusan' => 'Lulus']);
            $pageTitle = 'Data Siswa Lulusan';
        } else {
            $query->where(['status_kelulusan' => 'Aktif']);
            $pageTitle = 'Data Siswa Aktif';
        }

        // Search by NISN or Name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nisn', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        // Filter by Graduation Year
        if ($status == 'Lulus' && $request->filled('tahun_lulus')) {
            $query->where(['tahun_lulus' => $request->tahun_lulus]);
        }

        $students = $query->paginate(10)->appends($request->query());

        $years = [];
        if ($status == 'Lulus') {
            $years = \App\Models\Student::where(['status_kelulusan' => 'Lulus'])
                ->whereNotNull('tahun_lulus')
                ->distinct()
                ->orderByRaw('tahun_lulus DESC')
                ->pluck('tahun_lulus');
        }
        $classroomsList = \App\Models\Classroom::where(['is_active' => true])->orderByRaw('nama_kelas ASC')->get();

        return view('backend.adminlembaga.students.index', compact('students', 'pageTitle', 'status', 'years', 'classroomsList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classrooms = \App\Models\Classroom::where(['is_active' => true])->orderByRaw('nama_kelas ASC')->get();
        return view('backend.adminlembaga.students.create', compact('classrooms'));
    }

    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();
        $validated['status_kelulusan'] = 'Aktif';

        if ($request->hasFile('foto_profil')) {
            $validated['foto_profil'] = $request->file('foto_profil')->store('students', 'public');
        }

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
        $classrooms = \App\Models\Classroom::where(['is_active' => true])->orderByRaw('nama_kelas ASC')->get();
        return view('backend.adminlembaga.students.edit', compact('student', 'classrooms'));
    }

    public function update(UpdateStudentRequest $request, string $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('foto_profil')) {
            if ($student->foto_profil) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($student->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')->store('students', 'public');
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

    private function getPrintSettings()
    {
        return [
            'logoKab' => \App\Models\AppSetting::getSetting('logo_kabupaten'),
            'logoSchool' => \App\Models\AppSetting::getSetting('school_logo'),
            'schoolName' => \App\Models\AppSetting::getSetting('school_name', tenant('id')),
            'schoolAddress' => \App\Models\AppSetting::getSetting('school_address', 'Alamat Sekolah Belum Diisi'),
            'schoolEmail' => \App\Models\AppSetting::getSetting('school_email', '-'),
            'schoolNpsn' => \App\Models\AppSetting::getSetting('school_npsn', '-'),
            'schoolDistrictHeader' => \App\Models\AppSetting::getSetting('school_district_header', 'PEMERINTAH KABUPATEN CIANJUR'),
            'schoolCity' => \App\Models\AppSetting::getSetting('school_city', 'Cianjur'),
            'headmaster' => \App\Models\AppSetting::getSetting('school_headmaster_name', '..........................'),
            'nip' => \App\Models\AppSetting::getSetting('school_headmaster_nip', '..........................'),
            'signature' => \App\Models\AppSetting::getSetting('school_signature'),
            'stamp' => \App\Models\AppSetting::getSetting('school_stamp'),
        ];
    }

    public function print(string $id)
    {
        $student = \App\Models\Student::with('classroom')->findOrFail($id);
        $printSettings = $this->getPrintSettings();
        return view('backend.adminlembaga.students.print', compact('student', 'printSettings'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids) {
            return redirect()->back()->with('error', 'Tidak ada siswa yang dipilih.');
        }

        DB::transaction(function () use ($ids) {
            $students = \App\Models\Student::whereIn('id', $ids)->get();
            foreach ($students as $student) {
                if ($student->foto_profil) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($student->foto_profil);
                }
                $student->delete();
            }
        });

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

        $printSettings = $this->getPrintSettings();

        return view('backend.adminlembaga.students.print_bulk', compact('students', 'printSettings'));
    }

    public function downloadTemplate(Request $request)
    {
        $status = $request->input('status', 'Aktif');

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

        DB::transaction(function () use ($request, $targetClassroom) {
            \App\Models\Student::whereIn('id', $request->ids)->update([
                'classroom_id' => $targetClassroom->id,
                'kelas' => $targetClassroom->nama_kelas,
            ]);
        });

        return response()->json(['success' => true, 'message' => count($request->ids) . ' siswa berhasil dinaikkan ke kelas ' . $targetClassroom->nama_kelas]);
    }

    public function bulkGraduate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id',
            'graduation_year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        DB::transaction(function () use ($request) {
            \App\Models\Student::whereIn('id', $request->ids)->update([
                'status_kelulusan' => 'Lulus',
                'tahun_lulus' => $request->graduation_year,
            ]);
        });

        return response()->json(['success' => true, 'message' => count($request->ids) . ' siswa berhasil diluluskan.']);

    }
}
