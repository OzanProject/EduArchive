<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TenantUserController extends Controller
{
  /**
   * Display a listing of the operators.
   */
  public function index()
  {
    // Only show users belonging to this tenant and having 'operator' role
    $users = User::where('tenant_id', tenant('id'))
      ->where('role', 'operator')
      ->latest()
      ->paginate(10);

    return view('backend.adminlembaga.users.index', compact('users'));
  }

  /**
   * Show the form for creating a new operator.
   */
  public function create()
  {
    return view('backend.adminlembaga.users.create');
  }

  /**
   * Store a newly created operator in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => [
        'required',
        'string',
        'email',
        'max:255',
        Rule::unique('users')->where(function ($query) {
          return $query->where('tenant_id', tenant('id'));
        }), // Email unique per tenant or global? global is safer for login.
        // Actually, standard User table usually requires unique email globally.
        'unique:users,email'
      ],
      'password' => 'required|confirmed|min:8',
    ]);

    User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role' => 'operator',
      'tenant_id' => tenant('id'), // Scope to current tenant
    ]);

    return redirect()->route('adminlembaga.users.index')
      ->with('success', 'Operator berhasil ditambahkan.');
  }

  /**
   * Show the form for editing the specified operator.
   */
  public function edit($id)
  {
    $user = User::where('tenant_id', tenant('id'))
      ->where('role', 'operator')
      ->findOrFail($id);

    return view('backend.adminlembaga.users.edit', compact('user'));
  }

  /**
   * Update the specified operator in storage.
   */
  public function update(Request $request, $id)
  {
    $user = User::where('tenant_id', tenant('id'))
      ->where('role', 'operator')
      ->findOrFail($id);

    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users,email,' . $id,
      'password' => 'nullable|confirmed|min:8',
    ]);

    $data = [
      'name' => $request->name,
      'email' => $request->email,
    ];

    if ($request->filled('password')) {
      $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('adminlembaga.users.index')
      ->with('success', 'Data operator berhasil diperbarui.');
  }

  /**
   * Remove the specified operator from storage.
   */
  public function destroy($id)
  {
    $user = User::where('tenant_id', tenant('id'))
      ->where('role', 'operator')
      ->findOrFail($id);

    $user->delete();

    return redirect()->route('adminlembaga.users.index')
      ->with('success', 'Operator berhasil dihapus.');
  }
}
