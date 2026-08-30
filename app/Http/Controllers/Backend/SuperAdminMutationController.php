<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\StudentMutation;
use App\Models\Student;
use App\Models\Tenant;
use Illuminate\Http\Request;

class SuperAdminMutationController extends Controller
{
    public function index(Request $request)
    {
        $mutations = StudentMutation::with(['student', 'fromTenant', 'toTenant'])
            ->latest()
            ->paginate(15);

        return view('backend.superadmin.mutations.index', compact('mutations'));
    }

    public function returnStudent(Request $request, $id)
    {
        $mutation = StudentMutation::findOrFail($id);

        if ($mutation->status === 'returned') {
            return back()->with('error', 'Siswa sudah dikembalikan ke lembaga asalnya.');
        }

        $targetTenant = Tenant::find($mutation->from_tenant_id);
        $sourceTenant = Tenant::find($mutation->to_tenant_id);

        if (!$targetTenant || !$sourceTenant) {
            return back()->with('error', 'Data lembaga asal/tujuan sudah tidak valid.');
        }

        $error = null;

        $sourceTenant->run(function () use ($targetTenant, $sourceTenant, $mutation, &$error) {
            // Find student inside tenant context
            $student = Student::find($mutation->student_id);
            
            if (!$student) {
                $error = 'Siswa tidak ditemukan di lembaga tujuan (kemungkinan sudah dihapus atau dipindah lagi).';
                return;
            }

            \DB::table('documents')->where('student_id', $student->id)->update(['tenant_id' => $targetTenant->id]);
            \DB::table('graduations')->where('student_id', $student->id)->update(['tenant_id' => $targetTenant->id]);
            \DB::table('students')->where('id', $student->id)->update(['tenant_id' => $targetTenant->id, 'classroom_id' => null]);

            $mutation->update(['status' => 'returned']);
            
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'tenant_id' => $targetTenant->id,
                'action' => 'RETURN_MUTATION',
                'target_type' => Student::class,
                'target_id' => $student->id,
                'ip_address' => request()->ip(),
                'details' => [
                  'student_id' => $student->id,
                  'student_nisn' => $student->nisn ?? '-',
                  'student_nama' => $student->nama,
                  'target_tenant' => $targetTenant->nama_sekolah,
                  'from_tenant' => $sourceTenant->nama_sekolah,
                  'user_agent' => request()->userAgent()
                ]
            ]);
        });

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', 'Siswa berhasil dikembalikan ke lembaga asalnya.');
    }
}
