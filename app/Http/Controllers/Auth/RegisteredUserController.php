<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'npsn' => ['required', 'string', 'max:20', 'unique:tenants,npsn'],
            'id' => ['required', 'string', 'alpha_dash', 'max:50', 'unique:tenants,id'], // Validate Kode Sekolah
            'nama_sekolah' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', 'exists:school_levels,name'], // Validate against DB
            'alamat' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'], // Unique check handled manually per tenant if needed, but for now we let it be.
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create Tenant
        $tenant = \App\Models\Tenant::create([
            'id' => $request->id, // Use input ID
            'npsn' => $request->npsn,
            'nama_sekolah' => $request->nama_sekolah,
            'jenjang' => $request->jenjang,
            'alamat' => $request->alamat,
            'status_aktif' => 0, // Pending Approval by default
        ]);

        // Create Domain
        // Subdomain: id.eduarchive.test (or whatever central domain is)
        $subdomain = strtolower($request->id);
        $tenant->createDomain([
            'domain' => $subdomain, // Stancl/Tenancy will append central domain if configured or we valid it
        ]);

        // Create User inside Tenant
        $tenant->run(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin_sekolah',
            ]);

            event(new Registered($user));
        });

        // Redirect to Tenant Login
        // Path-based tenancy: http://domain.com/tenant_id/login

        $protocol = request()->secure() ? 'https://' : 'http://';
        $centralDomain = request()->getHost();
        $port = request()->getPort() != 80 && request()->getPort() != 443 ? ':' . request()->getPort() : '';

        // Construct Path Based URL
        $tenantUrl = $protocol . $centralDomain . $port . '/' . $tenant->id;

        return redirect($tenantUrl . '/login');
    }
}
