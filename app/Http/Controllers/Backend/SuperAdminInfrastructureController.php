<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\InfrastructureRequest;
use App\Models\Tenant;

class SuperAdminInfrastructureController extends Controller
{
    /**
     * Display a listing of all requests.
     */
    public function index(Request $request)
    {
        $query = InfrastructureRequest::with('tenant')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        $requests = $query->paginate(15);
        $tenants = Tenant::all();

        return view('backend.superadmin.infrastructure.index', compact('requests', 'tenants'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $infrastructure = InfrastructureRequest::with('tenant')->findOrFail($id);
        return view('backend.superadmin.infrastructure.show', compact('infrastructure'));
    }

    /**
     * Update the status of the request.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,in_progress,completed',
        ]);

        $infrastructure = InfrastructureRequest::findOrFail($id);
        $infrastructure->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Status usulan berhasil diperbarui.');
    }
}
