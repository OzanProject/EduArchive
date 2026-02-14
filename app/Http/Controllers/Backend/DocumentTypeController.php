<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DocumentType;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = DocumentType::latest()->get();
        return view('backend.superadmin.document_types.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.superadmin.document_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Merge checkbox values before validation to ensure boolean type
        $request->merge([
            'is_required' => $request->has('is_required'),
            'is_active' => $request->has('is_active'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:document_types,code',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
        ]);

        DocumentType::create($validated);

        return redirect()->route('superadmin.document-types.index')->with('success', 'Jenis Dokumen berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $type = DocumentType::findOrFail($id);
        return view('backend.superadmin.document_types.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $type = DocumentType::findOrFail($id);

        // Merge checkbox values before validation
        $request->merge([
            'is_required' => $request->has('is_required'),
            'is_active' => $request->has('is_active'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:document_types,code,' . $id,
            'description' => 'nullable|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $type->update($validated);

        return redirect()->route('superadmin.document-types.index')->with('success', 'Jenis Dokumen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $type = DocumentType::findOrFail($id);
        $type->delete();

        return redirect()->route('superadmin.document-types.index')->with('success', 'Jenis Dokumen berhasil dihapus.');
    }
}
