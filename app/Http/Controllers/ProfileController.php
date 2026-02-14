<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Failsafe: Ensure tenancy is initialized if parameter is present
        if (!tenant() && $tenantId = $request->route('tenant')) {
            $tenant = \App\Models\Tenant::find($tenantId);
            if ($tenant) {
                tenancy()->initialize($tenant);
                \Illuminate\Support\Facades\Log::info('Manually Initialized Tenancy: ' . $tenantId);
            }
        }

        // Reload user to ensure correct DB connection (standard Auth user might be stale/central)
        $user = $request->user()->fresh();

        \Illuminate\Support\Facades\Log::info('Profile Update Hit', [
            'user_id' => $user->id,
            'tenant' => tenant('id'),
            'db_connection' => $user->getConnectionName()
        ]);

        try {
            $user->fill($request->validated());

            if ($request->hasFile('avatar')) {
                \Illuminate\Support\Facades\Log::info('Processing Avatar Upload');
                // Delete old avatar if exists
                if ($user->avatar) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                }

                // Store new avatar
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
                \Illuminate\Support\Facades\Log::info('Avatar Stored at: ' . $path);
            }

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();
            \Illuminate\Support\Facades\Log::info('Profile Saved Successfully');

            return back()->with('status', 'profile-updated');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Profile Update Failed: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            return back()->with('error', 'Update Failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
