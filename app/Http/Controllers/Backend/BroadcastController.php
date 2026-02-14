<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $broadcasts = Broadcast::latest()->paginate(10);
    return view('backend.superadmin.broadcasts.index', compact('broadcasts'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('backend.superadmin.broadcasts.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'content' => 'required|string',
      'type' => 'required|in:info,warning,danger,success',
    ]);

    // Checkbox returns "on" or null, so we handle boolean manually if not present in validated as boolean
    // But validation rule 'boolean' handles 1, 0, "1", "0", "true", "false".
    // HTML checkbox not checked doesn't send value.
    $validated['is_active'] = $request->has('is_active');

    Broadcast::create($validated);

    return redirect()->route('superadmin.broadcasts.index')->with('success', 'Broadcast berhasil dibuat.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Broadcast $broadcast)
  {
    $broadcast->delete();
    return redirect()->route('superadmin.broadcasts.index')->with('success', 'Broadcast berhasil dihapus.');
  }
}
