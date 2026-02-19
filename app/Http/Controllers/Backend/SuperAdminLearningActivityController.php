<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LearningActivity;
use App\Models\Tenant;
use Illuminate\Http\Request;

class SuperAdminLearningActivityController extends Controller
{
    /**
     * Display a listing of all activities from all tenants.
     */
    public function index(Request $request)
    {
        $query = LearningActivity::with('tenant')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        $activities = $query->paginate(15);
        $tenants = Tenant::all();

        return view('backend.superadmin.learning_activities.index', compact('activities', 'tenants'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activity = LearningActivity::with('tenant')->findOrFail($id);
        return view('backend.superadmin.learning_activities.show', compact('activity'));
    }

    /**
     * Update the status and provide notes.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'status_notes' => 'nullable|string',
        ]);

        $activity = LearningActivity::findOrFail($id);
        $activity->update([
            'status' => $validated['status'],
            'status_notes' => $validated['status_notes'] ?? $activity->status_notes,
        ]);

        return redirect()->back()->with('success', 'Status kegiatan berhasil diperbarui.');
    }
}
