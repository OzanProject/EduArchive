<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;

class IntegrationController extends Controller
{
    /**
     * Get all students for the authenticated tenant.
     */
    public function getStudents(Request $request)
    {
        // Sanctum token is authenticated. 
        // With stancl/tenancy, the tenant is resolved via the token or we can just query using tenant_id
        // Wait, sanctum authenticates the "tokenable" model.
        // Since the token belongs to the Tenant model, $request->user() is actually the Tenant instance!
        $tenant = $request->user();

        if (!$tenant || !($tenant instanceof \App\Models\Tenant)) {
            return response()->json(['error' => 'Unauthorized or Invalid Token Owner'], 401);
        }

        // We can optionally support pagination
        $limit = $request->query('limit', 100);

        $students = Student::where('tenant_id', $tenant->id)
            ->with(['classroom' => function($q) {
                $q->select('id', 'name');
            }])
            ->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $students->items(),
            'meta' => [
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total(),
            ]
        ]);
    }

    /**
     * Get all teachers for the authenticated tenant.
     */
    public function getTeachers(Request $request)
    {
        $tenant = $request->user();

        if (!$tenant || !($tenant instanceof \App\Models\Tenant)) {
            return response()->json(['error' => 'Unauthorized or Invalid Token Owner'], 401);
        }

        $limit = $request->query('limit', 100);

        $teachers = Teacher::where('tenant_id', $tenant->id)->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $teachers->items(),
            'meta' => [
                'current_page' => $teachers->currentPage(),
                'last_page' => $teachers->lastPage(),
                'per_page' => $teachers->perPage(),
                'total' => $teachers->total(),
            ]
        ]);
    }

    /**
     * Get all classrooms (rombel) for the authenticated tenant.
     */
    public function getClassrooms(Request $request)
    {
        $tenant = $request->user();

        if (!$tenant || !($tenant instanceof \App\Models\Tenant)) {
            return response()->json(['error' => 'Unauthorized or Invalid Token Owner'], 401);
        }

        $classrooms = Classroom::where('tenant_id', $tenant->id)
            ->with(['homeroomTeacher' => function($q) {
                $q->select('id', 'name', 'nip');
            }])
            ->get(); // Classrooms are usually few, so get() is fine.

        return response()->json([
            'status' => 'success',
            'data' => $classrooms
        ]);
    }
}
