<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LearningActivity;
use Illuminate\Http\Request;

class LearningActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = LearningActivity::latest()->paginate(15);
        return view('backend.adminlembaga.learning_activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.adminlembaga.learning_activities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'method' => 'required|in:daring,luring,hybrid',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'time_start' => 'required',
            'time_end' => 'required|after:time_start',
            'description' => 'nullable|string',
            'activity_image' => 'nullable|image|max:2048', // 2MB Max
        ]);

        if ($request->hasFile('activity_image')) {
            $path = $request->file('activity_image')->store('learning_activities/' . tenant('id'), 'public');
            $validated['activity_image'] = $path;
        }

        LearningActivity::create($validated);

        return redirect()->route('adminlembaga.learning-activities.index')
            ->with('success', 'Kegiatan pembelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LearningActivity $learningActivity)
    {
        return view('backend.adminlembaga.learning_activities.show', compact('learningActivity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LearningActivity $learningActivity)
    {
        if (!in_array($learningActivity->status, ['pending', 'rejected'])) {
            return redirect()->route('adminlembaga.learning-activities.index')
                ->with('error', 'Hanya kegiatan dengan status pending atau rejected yang dapat diubah.');
        }

        return view('backend.adminlembaga.learning_activities.edit', compact('learningActivity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LearningActivity $learningActivity)
    {
        if (!in_array($learningActivity->status, ['pending', 'rejected'])) {
            return redirect()->route('adminlembaga.learning-activities.index')
                ->with('error', 'Hanya kegiatan dengan status pending atau rejected yang dapat diubah.');
        }

        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'method' => 'required|in:daring,luring,hybrid',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'time_start' => 'required',
            'time_end' => 'required|after:time_start',
            'description' => 'nullable|string',
            'activity_image' => 'nullable|image|max:2048', // 2MB Max
        ]);

        if ($request->hasFile('activity_image')) {
            // Delete old image if exists
            if ($learningActivity->activity_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($learningActivity->activity_image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($learningActivity->activity_image);
            }

            $path = $request->file('activity_image')->store('learning_activities/' . tenant('id'), 'public');
            $validated['activity_image'] = $path;
        }

        // If updated from rejected, reset status to pending
        if ($learningActivity->status === 'rejected') {
            $validated['status'] = 'pending';
        }

        $learningActivity->update($validated);

        return redirect()->route('adminlembaga.learning-activities.index')
            ->with('success', 'Kegiatan pembelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LearningActivity $learningActivity)
    {
        if (!in_array($learningActivity->status, ['pending', 'rejected'])) {
            return redirect()->route('adminlembaga.learning-activities.index')
                ->with('error', 'Hanya kegiatan dengan status pending atau rejected yang dapat dihapus.');
        }

        // Delete image if exists
        if ($learningActivity->activity_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($learningActivity->activity_image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($learningActivity->activity_image);
        }

        $learningActivity->delete();

        return redirect()->route('adminlembaga.learning-activities.index')
            ->with('success', 'Kegiatan pembelajaran berhasil dihapus.');
    }
}
