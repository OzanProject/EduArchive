<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SchoolDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = \App\Models\SchoolDocument::with('uploader')->latest()->get();
        return view('backend.adminlembaga.school_documents.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'file_path' => 'required|file|max:10240', // 10MB
        ]);

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileSize = $file->getSize();

            // Check Storage Limit
            $tenant = auth()->user()->tenant;
            if ($tenant && !$tenant->checkStorageLimit($fileSize)) {
                return redirect()->back()->withErrors(['file_path' => 'Kapasitas penyimpanan sekolah sudah penuh. Hubungi Super Admin.'])->withInput();
            }

            $path = $file->store('school_documents', 'public');

            \App\Models\SchoolDocument::create([
                'title' => $request->title,
                'category' => $request->category,
                'file_path' => $path,
                'file_size' => $fileSize,
                'mime_type' => $file->getMimeType(),
                'description' => $request->description,
                'uploaded_by' => auth()->id(),
            ]);

            // Update Storage Usage
            if ($tenant) {
                $tenant->updateStorageUsage($fileSize, true);
            }
        }

        return redirect()->back()->with('success', 'Arsip dokumen berhasil disimpan.');
    }

    public function destroy($id)
    {
        $doc = \App\Models\SchoolDocument::findOrFail($id);
        if ($doc->file_path) {
            $fileSize = $doc->file_size ?? 0;

            // Fallback for legacy documents without file_size
            if ($fileSize === 0 && \Illuminate\Support\Facades\Storage::disk('public')->exists($doc->file_path)) {
                $fileSize = \Illuminate\Support\Facades\Storage::disk('public')->size($doc->file_path);
            }

            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($doc->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->file_path);
            }

            // Update Storage Usage (Decrement)
            $tenant = auth()->user()->tenant;
            if ($tenant && $fileSize > 0) {
                $tenant->updateStorageUsage($fileSize, false);
            }
        }
        $doc->delete();
        return redirect()->back()->with('success', 'Arsip dokumen berhasil dihapus.');
    }
}
