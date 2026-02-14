<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next, string ...$guards): Response
  {
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
      if (Auth::guard($guard)->check()) {
        $user = Auth::guard($guard)->user();

        // 1. Super Admin -> Central Dashboard
        if ($user->role === 'superadmin') {
          // Check if we are on a tenant domain? 
          // Ideally superadmin should be on central, but if they are on tenant, 
          // they might want to access tenant dashboard?
          // But your routes say superadmin dashboard is only on central.
          return redirect()->route('superadmin.dashboard');
        }

        // 2. Tenant Users (Admin Sekolah / Operator)
        if (in_array($user->role, ['admin_sekolah', 'operator'])) {
          // If we are ALREADY in a tenant context, redirect to dashboard
          if (tenant()) {
            if ($user->role === 'operator') {
              return redirect()->route('operator.dashboard', ['tenant' => tenant('id')]);
            }
            return redirect()->route('adminlembaga.dashboard', ['tenant' => tenant('id')]);
          }

          // If we are on CENTRAL domain but logged in as Tenant User
          // We can't easily know WHICH tenant they belong to unless we store it in session.
          // But usually, they shouldn't be here.
          // Redirect to a generic page or logout?
          // Ideally, if we could know their tenant, we redirect. 
          // For now, let's redirect to HOME or show a "Go to your school" message.
          // A safe bet is redirecting to root, where the landing page might handle them?
          // Or logout them?
          // Let's redirect to '/' for now.
          return redirect('/');
        }

        // 3. General Fallback
        return redirect('/dashboard');
      }
    }

    return $next($request);
  }
}
