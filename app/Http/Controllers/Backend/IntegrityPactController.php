<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\IntegrityPact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IntegrityPactController extends Controller
{
    public function index()
    {
        $pacts = IntegrityPact::latest()->paginate(10);
        return view('backend.adminlembaga.integrity_pacts.index', compact('pacts'));
    }

    public function create()
    {
        return view('backend.adminlembaga.integrity_pacts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file_path' => 'required|mimes:pdf|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('file_path')) {
            $path = $request->file('file_path')->store('integrity_pacts/' . tenant('id'), 'public');
            $validated['file_path'] = $path;
        }

        IntegrityPact::create($validated);

        if (auth()->user()->role === 'operator') {
            return redirect()->route('operator.integrity-pacts.index')->with('success', 'Fakta Integritas berhasil diunggah.');
        }

        return redirect()->route('adminlembaga.integrity-pacts.index')
            ->with('success', 'Fakta Integritas berhasil diunggah dan menunggu persetujuan.');
    }

    public function edit(IntegrityPact $integrityPact)
    {
        if ($integrityPact->status === 'approved') {
            return back()->with('error', 'Fakta Integritas yang sudah disetujui tidak dapat diubah.');
        }

        return view('backend.adminlembaga.integrity_pacts.edit', compact('integrityPact'));
    }

    public function update(Request $request, IntegrityPact $integrityPact)
    {
        if ($integrityPact->status === 'approved') {
            return back()->with('error', 'Fakta Integritas yang sudah disetujui tidak dapat diubah.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file_path' => 'nullable|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('file_path')) {
            if ($integrityPact->file_path && Storage::disk('public')->exists($integrityPact->file_path)) {
                Storage::disk('public')->delete($integrityPact->file_path);
            }
            $path = $request->file('file_path')->store('integrity_pacts/' . tenant('id'), 'public');
            $validated['file_path'] = $path;
        }

        if ($integrityPact->status === 'rejected') {
            $validated['status'] = 'pending';
        }

        $integrityPact->update($validated);
        
        if (auth()->user()->role === 'operator') {
            return redirect()->route('operator.integrity-pacts.index')->with('success', 'Fakta Integritas berhasil diperbarui.');
        }

        return redirect()->route('adminlembaga.integrity-pacts.index')
            ->with('success', 'Fakta Integritas berhasil diperbarui.');
    }

    public function destroy(IntegrityPact $integrityPact)
    {
        if ($integrityPact->file_path && Storage::disk('public')->exists($integrityPact->file_path)) {
            Storage::disk('public')->delete($integrityPact->file_path);
        }

        $integrityPact->delete();

        return back()->with('success', 'Fakta Integritas berhasil dihapus.');
    }
}
