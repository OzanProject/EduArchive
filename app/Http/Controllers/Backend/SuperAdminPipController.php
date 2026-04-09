<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PipData;
use App\Models\Tenant;

class SuperAdminPipController extends Controller
{
    public function index(Request $request)
    {
        $tenantScope = \Stancl\Tenancy\Database\TenantScope::class;
        $query = PipData::withoutGlobalScope($tenantScope)->with('tenant')->latest();

        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $allPip = $query->paginate(20)->appends($request->query());
        $tenants = Tenant::where('status_aktif', 1)->get();
        $pageTitle = "Monitoring Data Penerima PIP Lembaga";

        return view('backend.superadmin.pip.index', compact('allPip', 'tenants', 'pageTitle'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:usulan_sekolah,diproses_dinas,disetujui,ditolak',
            'pesan_dinas' => 'nullable|string'
        ]);

        $pip = PipData::withoutGlobalScope(\Stancl\Tenancy\Database\TenantScope::class)->findOrFail($id);
        
        $pip->update([
            'status' => $request->status,
            'pesan_dinas' => $request->pesan_dinas,
        ]);

        return redirect()->back()->with('success', "Status data PIP siswa {$pip->nama_siswa} berhasil diperbarui (Terverifikasi).");
    }

    public function destroy($id)
    {
        $pip = PipData::withoutGlobalScope(\Stancl\Tenancy\Database\TenantScope::class)->findOrFail($id);
        $pip->delete();
        
        return redirect()->back()->with('success', 'Data penerima PIP tersebut berhasil dihapus seluruhnya dari sistem.');
    }
}
