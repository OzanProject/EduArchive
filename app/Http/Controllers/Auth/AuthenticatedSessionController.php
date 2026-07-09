<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $tenants = [];
        $demoUsers = [];

        // Only load tenants and demo users if not in a tenant context (Central) or if needed
        if (!tenant()) {
            $tenants = \App\Models\Tenant::all();

            // For smoother UX in local/demo:
            // Get Super Admin demo
            $demoUsers['dinas'] = \App\Models\User::where('role', 'superadmin')->first();
        } else {
            // If in Tenant context, get School Admin / Operator demo
            $demoUsers['school_admin'] = \App\Models\User::where('role', 'admin_sekolah')->first();
            $demoUsers['operator'] = \App\Models\User::where('role', 'operator')->first();
        }

        return view('auth.login', compact('tenants', 'demoUsers'));
    }

    /**
     * Logout and redirect with error.
     */
    private function logoutAndRedirectError(Request $request, string $message): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors(['email' => $message]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Check if Tenant is Active
        if (tenant() && !tenant()->status_aktif) {
            return $this->logoutAndRedirectError($request, 'Akun sekolah Anda belum diaktifkan oleh pihak ' . config('app.name') . '. Harap tunggu persetujuan.');
        }

        if ($user->hasRole('superadmin')) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->hasRole('admin_sekolah') || $user->hasRole('operator')) {
            return tenant()
                ? redirect()->route($user->hasRole('operator') ? 'operator.dashboard' : 'adminlembaga.dashboard', ['tenant' => tenant('id')])
                : $this->logoutAndRedirectError($request, 'Akun Sekolah harus login melalui Portal Sekolah (pilih tab Sekolah).');
        }

        return tenant() ? redirect()->route('dashboard', ['tenant' => tenant('id')]) : redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
