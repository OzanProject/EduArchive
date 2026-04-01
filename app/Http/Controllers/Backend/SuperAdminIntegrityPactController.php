<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\IntegrityPact;
use Illuminate\Http\Request;

class SuperAdminIntegrityPactController extends Controller
{
    public function index()
    {
        $pacts = IntegrityPact::with('tenant')->latest()->paginate(15);
        return view('backend.superadmin.integrity_pacts.index', compact('pacts'));
    }

    public function show($id)
    {
        $pact = IntegrityPact::with('tenant')->findOrFail($id);
        return view('backend.superadmin.integrity_pacts.show', compact('pact'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pact = IntegrityPact::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'status_notes' => 'nullable|string',
        ]);

        $pact->update($validated);

        return redirect()->route('superadmin.monitoring.integrity-pacts.index')
            ->with('success', 'Status Fakta Integritas berhasil diperbarui.');
    }
}
