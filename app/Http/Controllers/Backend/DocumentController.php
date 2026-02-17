<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Document::with(['student', 'uploader'])->latest();

        $status = $request->get('status', 'Aktif');

        // Filter based on Student Status
        $query->whereHas('student', function ($q) use ($status) {
            $q->where('status_kelulusan', $status);
        });

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $documents = $query->paginate(10);

        return view('backend.adminlembaga.documents.index', compact('documents', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $students = \App\Models\Student::with('classroom')->orderBy('status_kelulusan')->orderBy('nama')->get();

        // Fetch document types from Central DB
        $documentTypes = \App\Models\DocumentType::where('is_active', true)->orderBy('name')->get();

        $selectedStudentId = $request->get('student_id');
        $selectedType = $request->get('type');

        return view('backend.adminlembaga.documents.create', compact('students', 'documentTypes', 'selectedStudentId', 'selectedType'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'document_type' => 'required|string',
            'file_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:51200', // 50MB
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileSize = $file->getSize();

            // Check Storage Limit
            $tenant = auth()->user()->tenant; // User (Operator/AdminLembaga) belongs to tenant
            if ($tenant && !$tenant->checkStorageLimit($fileSize)) {
                return redirect()->back()->withErrors(['file_path' => 'Kapasitas penyimpanan sekolah sudah penuh. Hubungi Super Admin.'])->withInput();
            }

            $path = $file->store('documents/' . $validated['student_id'], 'public');

            \App\Models\Document::create([
                'student_id' => $validated['student_id'],
                'document_type' => $validated['document_type'],
                'file_path' => $path,
                'file_size' => $fileSize,
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => auth()->id(),
                'keterangan' => $validated['keterangan'],
                'verified_at' => now(),
            ]);

            // Update Storage Usage
            if ($tenant) {
                $tenant->updateStorageUsage($fileSize, true);
            }
        }

        return redirect()->route((auth()->user()->role === 'operator' ? 'operator.' : 'adminlembaga.') . 'documents.index')->with('success', 'Dokumen berhasil diupload.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $document = \App\Models\Document::findOrFail($id);

        // Security check: Ensure file exists
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->response($document->file_path);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy($id)
    {
        $document = \App\Models\Document::findOrFail($id);

        if ($document->file_path) {
            $fileSize = $document->file_size; // Ensure file_size exists in model
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($document->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($document->file_path);
            }

            // Update Storage Usage (Decrement)
            $tenant = auth()->user()->tenant;
            if ($tenant) {
                $tenant->updateStorageUsage($fileSize, false);
            }
        }

        $document->delete();

        return redirect()->route((auth()->user()->role === 'operator' ? 'operator.' : 'adminlembaga.') . 'documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
