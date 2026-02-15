<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
  /**
   * Display the user's profile form.
   */
  public function edit(Request $request): View
  {
    $user = $request->user();
    // Force reload to check connection
    if (Auth::check()) {
      $user = Auth::user()->fresh();
    }

    return view('tenant.profile.edit', [
      'user' => $user,
    ]);
  }

  /**
   * Update the user's profile information.
   */
  public function update(Request $request): RedirectResponse
  {
    $user = $request->user();

    // Ensure we are working with the latest user instance from the correct DB
    $user = Auth::user();
    if ($user) {
      $user = $user->fresh();
    }

    $validated = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => [
        'required',
        'string',
        'lowercase',
        'email',
        'max:255',
        // Unique check on the 'users' table of the CURRENT connection
        Rule::unique('users')->ignore($user->id),
      ],
      'avatar' => ['nullable', 'image', 'max:1024'], // 1MB Max
    ]);

    $user->fill($validated);

    if ($user->isDirty('email')) {
      $user->email_verified_at = null;
    }

    if ($request->hasFile('avatar')) {
      // Delete old avatar if exists
      if ($user->avatar) {
        Storage::disk('public')->delete($user->avatar);
      }

      // Store new avatar in 'avatars' directory on public disk
      $path = $request->file('avatar')->store('avatars', 'public');
      $user->avatar = $path;
    }

    $user->save();

    return back()->with('status', 'profile-updated');
  }

  /**
   * Update the user's password.
   */
  public function updatePassword(Request $request): RedirectResponse
  {
    $validated = $request->validateWithBag('updatePassword', [
      'current_password' => ['required', 'current_password'],
      'password' => ['required', Password::defaults(), 'confirmed'],
    ]);

    $request->user()->update([
      'password' => Hash::make($validated['password']),
    ]);

    return back()->with('status', 'password-updated');
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

    return redirect('/');
  }
}
