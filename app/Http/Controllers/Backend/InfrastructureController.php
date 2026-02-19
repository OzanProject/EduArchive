<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InfrastructureRequest;
use Illuminate\Http\Request;

class InfrastructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = InfrastructureRequest::latest()->paginate(10);
        return view('backend.adminlembaga.infrastructure.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.adminlembaga.infrastructure.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:RKB,REHAB',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'proposed_budget' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['media_path'] = $request->file('thumbnail')->store('infrastructure', 'public');
        }

        InfrastructureRequest::create($validated);

        return redirect()->route('adminlembaga.infrastructure.index')
            ->with('success', 'Usulan sarana prasarana berhasil dikirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InfrastructureRequest $infrastructure)
    {
        return view('backend.adminlembaga.infrastructure.show', compact('infrastructure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InfrastructureRequest $infrastructure)
    {
        if ($infrastructure->status !== 'pending') {
            return redirect()->route('adminlembaga.infrastructure.index')
                ->with('error', 'Hanya usulan dengan status pending yang dapat diubah.');
        }

        return view('backend.adminlembaga.infrastructure.edit', compact('infrastructure'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InfrastructureRequest $infrastructure)
    {
        if ($infrastructure->status !== 'pending') {
            return redirect()->route('adminlembaga.infrastructure.index')
                ->with('error', 'Hanya usulan dengan status pending yang dapat diubah.');
        }

        $validated = $request->validate([
            'type' => 'required|in:RKB,REHAB',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'proposed_budget' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old file if exists
            if ($infrastructure->media_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($infrastructure->media_path);
            }
            $validated['media_path'] = $request->file('thumbnail')->store('infrastructure', 'public');
        }

        $infrastructure->update($validated);

        return redirect()->route('adminlembaga.infrastructure.index')
            ->with('success', 'Usulan sarana prasarana berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InfrastructureRequest $infrastructure)
    {
        if ($infrastructure->status !== 'pending') {
            return redirect()->route('adminlembaga.infrastructure.index')
                ->with('error', 'Hanya usulan dengan status pending yang dapat dihapus.');
        }

        if ($infrastructure->media_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($infrastructure->media_path);
        }

        $infrastructure->delete();

        return redirect()->route('adminlembaga.infrastructure.index')
            ->with('success', 'Usulan sarana prasarana berhasil dihapus.');
    }
}
