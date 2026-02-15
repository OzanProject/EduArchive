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

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $documents = $query->paginate(10);

        return view('backend.adminlembaga.documents.index', compact('documents'));
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
            $path = $file->store('documents/' . $validated['student_id'], 'public');

            \App\Models\Document::create([
                'student_id' => $validated['student_id'],
                'document_type' => $validated['document_type'],
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => auth()->id(),
                'keterangan' => $validated['keterangan'],
                'verified_at' => now(), // Auto-verify if uploaded by admin? Or leave null? Let's auto-verify for admin.
            ]);
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
            \Illuminate\Support\Facades\Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route((auth()->user()->role === 'operator' ? 'operator.' : 'adminlembaga.') . 'documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
