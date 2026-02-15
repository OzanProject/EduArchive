<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\Models\SchoolLevel; // Add this

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tenant::latest();

        if ($request->has('table_search') && $request->table_search != '') {
            $search = $request->table_search;
            $query->where('npsn', 'like', "%{$search}%")
                ->orWhere('nama_sekolah', 'like', "%{$search}%");
        }

        $tenants = $query->get();
        return view('backend.superadmin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schoolLevels = SchoolLevel::orderBy('sequence')->get();
        return view('backend.superadmin.tenants.create', compact('schoolLevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => 'required|string|max:20|unique:tenants,npsn',
            'jenjang' => 'required|exists:school_levels,name', // Changed from in: to exists:
            'id' => 'required|string|alpha_dash|max:50|unique:tenants,id', // Changed domain to id
            'alamat' => 'nullable|string',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8',
            'subscription_plan' => 'nullable|string',
            'storage_limit' => 'nullable|integer|min:0',
            'status_aktif' => 'boolean',
        ]);

        // Create Tenant (Single DB Mode - just a record)
        $tenant = Tenant::create([
            'id' => $request->id,
            'npsn' => $request->npsn,
            'nama_sekolah' => $request->nama_sekolah,
            'jenjang' => $request->jenjang,
            'alamat' => $request->alamat,
            'subscription_plan' => $request->subscription_plan,
            'storage_limit' => $request->storage_limit ?? 1073741824, // Default 1GB
            'status_aktif' => $request->has('status_aktif'),
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('tenant_logos', 'public');
            $tenant->update(['logo' => $path]);
        }

        // Initialize Tenancy to Switch Scope (Even if Single DB, this sets the global scope)
        // However, standard `run` method usually switches context.
        // For Single DB with BelongsToTenant, we need `tenancy()->initialize($tenant)` 
        // OR manually set the tenant if the trait relies on `Tenant::current()`.

        $tenant->run(function () use ($request) {
            \App\Models\User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->admin_password),
                'role' => 'admin_sekolah',
                'email_verified_at' => now(),
            ]);
        });

        return redirect()->route('superadmin.tenants.index')->with('success', 'Sekolah berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tenant = Tenant::find($id);
        $schoolLevels = SchoolLevel::orderBy('sequence')->get();

        // Fetch Admin User from Tenant Database
        $admin = $tenant->run(function () {
            return \App\Models\User::where('role', 'admin_sekolah')->first();
        });

        return view('backend.superadmin.tenants.edit', compact('tenant', 'admin', 'schoolLevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tenant = Tenant::find($id);

        $validated = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => ['required', 'string', 'max:20', Rule::unique('tenants')->ignore($tenant->id)],
            'jenjang' => 'required|exists:school_levels,name', // Changed from in: to exists:
            'alamat' => 'nullable|string',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'nullable|string|min:8',
            'storage_limit' => 'nullable|integer|min:0',
            'subscription_plan' => 'nullable|string',
        ]);

        $tenant->update([
            'nama_sekolah' => $request->nama_sekolah,
            'npsn' => $request->npsn,
            'jenjang' => $request->jenjang,
            'alamat' => $request->alamat,
            'storage_limit' => $request->storage_limit,
            'subscription_plan' => $request->subscription_plan,
            'status_aktif' => $request->has('status_aktif'),
        ]);

        // Sync Essential Data and Upload Logo in Tenant Context
        $tenant->run(function () use ($request, $tenant) {
            \App\Models\AppSetting::updateOrCreate(['key' => 'school_name'], ['value' => $request->nama_sekolah]);
            \App\Models\AppSetting::updateOrCreate(['key' => 'school_npsn'], ['value' => $request->npsn]);

            if ($request->filled('alamat')) {
                \App\Models\AppSetting::updateOrCreate(['key' => 'school_address'], ['value' => $request->alamat]);
            }

            // Handle Logo Upload INSIDE Tenant Context
            if ($request->hasFile('logo')) {
                // Delete old logo if exists (relative to tenant disk)
                if ($tenant->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($tenant->logo)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($tenant->logo);
                }

                // Store new logo (Tenancy bootstrapper ensures this goes to tenant directory)
                $path = $request->file('logo')->store('tenant_logos', 'public');

                // Update Tenant Record (Central DB, but we need to update the attribute)
                $tenant->update(['logo' => $path]);

                // Update AppSetting with URL accessible by Tenant
                // tenant_asset() generally handles the public asset URL generation for the tenant
                // Or we can use Storage::url($path), which inside tenant context matches the storage structure
                $url = \Illuminate\Support\Facades\Storage::url($path);

                \App\Models\AppSetting::updateOrCreate(['key' => 'school_logo'], ['value' => $url, 'type' => 'image']);
            }

            // Clear cache to reflect changes immediately
            \Illuminate\Support\Facades\Cache::forget('app_settings_' . $tenant->id);
            \Illuminate\Support\Facades\Cache::forget('app_settings_global');
        });

        // Update Admin User for this Tenant
        $tenant->run(function () use ($request) {
            $admin = \App\Models\User::where('role', 'admin_sekolah')->first();
            if ($admin) {
                $userData = [
                    'name' => $request->admin_name,
                    'email' => $request->admin_email,
                ];

                if ($request->filled('admin_password')) {
                    $userData['password'] = \Illuminate\Support\Facades\Hash::make($request->admin_password);
                }

                $admin->update($userData);
            }
        });

        return redirect()->route('superadmin.tenants.index')->with('success', 'Data sekolah berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tenant = Tenant::findOrFail($id);

        // Single DB Mode: Deleting the tenant record automatically cascades to related data via foreign keys.
        // No manual database deletion needed.
        $tenant->delete();

        return redirect()->route('superadmin.tenants.index')->with('success', 'Sekolah berhasil dihapus!');
    }

    /**
     * Update the status of the specified tenant.
     */
    public function updateStatus(Request $request, string $id)
    {
        $tenant = Tenant::find($id);
        $status = $request->input('status'); // 'active' or 'suspended'

        // Map string status to boolean logic if needed, or stick to boolean column
        // Assuming status_aktif is boolean: 1 = active, 0 = suspended
        $isActive = $status === 'active';

        $tenant->update([
            'status_aktif' => $isActive
        ]);

        $message = $isActive ? 'Sekolah berhasil diaktifkan kembali.' : 'Sekolah berhasil disuspend.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Serve tenant file for Super Admin.
     */
    public function asset($tenantId, $path)
    {
        $tenant = Tenant::findOrFail($tenantId);

        // Initialize tenancy to get the correct storage path
        tenancy()->initialize($tenant);

        $filePath = storage_path('app/public/' . $path);

        tenancy()->end(); // End tenancy to return to central context

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath);
    }
}
