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
            $path = $request->file('file_path')->store('school_documents', 'public');

            \App\Models\SchoolDocument::create([
                'title' => $request->title,
                'category' => $request->category,
                'file_path' => $path,
                'description' => $request->description,
                'uploaded_by' => auth()->id(),
            ]);
        }

        return redirect()->back()->with('success', 'Arsip dokumen berhasil disimpan.');
    }

    public function destroy($id)
    {
        $doc = \App\Models\SchoolDocument::findOrFail($id);
        if ($doc->file_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->file_path);
        }
        $doc->delete();
        return redirect()->back()->with('success', 'Arsip dokumen berhasil dihapus.');
    }
}
