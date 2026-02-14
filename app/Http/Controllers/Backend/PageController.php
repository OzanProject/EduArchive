<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::latest()->get();
        return view('backend.superadmin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.superadmin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $page = new Page();
        $page->title = $request->title;
        $page->slug = Str::slug($request->title);
        $page->content = $request->content;
        $page->is_published = $request->has('is_published');
        $page->save();

        return redirect()->route('superadmin.pages.index')->with('success', 'Halaman berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used in backend
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $page = Page::findOrFail($id);
        return view('backend.superadmin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $page = Page::findOrFail($id);
        $page->title = $request->title;
        // Optional: Update slug if title changes, or keep it fixed to avoid breaking links
        if ($page->title !== $request->title) {
            $page->slug = Str::slug($request->title);
        }
        $page->content = $request->content;
        $page->is_published = $request->has('is_published');
        $page->save();

        return redirect()->route('superadmin.pages.index')->with('success', 'Halaman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()->route('superadmin.pages.index')->with('success', 'Halaman berhasil dihapus.');
    }
}
