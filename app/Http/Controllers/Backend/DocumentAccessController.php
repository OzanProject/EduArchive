<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\AuditLog;

class DocumentAccessController extends Controller
{
    public function requestAccess(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required',
            'student_nisn' => 'required',
            'document_id' => 'required',
            'reason' => 'required|string|min:5',
        ]);

        $tenant = Tenant::findOrFail($request->tenant_id);

        // 1. Find Document in Tenant Context
        $document = $tenant->run(function () use ($request) {
            return \App\Models\Document::findOrFail($request->document_id);
        });

        // 2. Log Access
        AuditLog::create([
            'user_id' => auth()->id(),
            'tenant_id' => $request->tenant_id,
            'action' => 'DOCUMENT_ACCESS',
            'target_type' => \App\Models\Document::class,
            'target_id' => $request->document_id,
            'ip_address' => $request->ip(),
            'details' => json_encode([
                'student_nisn' => $request->student_nisn,
                'document_name' => $document->jenis_dokumen,
                'reason' => $request->reason,
                'user_agent' => $request->userAgent()
            ]),
        ]);

        // 3. Generate View URL (Simulated)
        // In real app, generate a signed URL to a secure route serving the file
        // For now, return a success response with the mock content or redirect

        return response()->json([
            'status' => 'success',
            'message' => 'Akses diberikan. Mengarahkan ke dokumen...',
            'url' => route('superadmin.monitoring.access_document', [
                'tenant_id' => $request->tenant_id,
                'nisn' => $request->student_nisn,
                'document_id' => $request->document_id
            ]) // Re-using the existing route for the actual view, but now it's "authorized" via front-end flow
            // Ideally, the view route should ALSO check if a log exists recently, but for this prototype, we rely on the flow.
        ]);
    }
}
