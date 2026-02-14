<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $users = User::orderByRaw("CASE WHEN id = 1 THEN 0 ELSE 1 END, created_at DESC")->paginate(10);
    return view('backend.superadmin.users.index', compact('users'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $roles = Role::pluck('name', 'name')->all();
    return view('backend.superadmin.users.create', compact('roles'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|same:confirm-password',
      'roles' => 'required'
    ]);

    $input = $request->all();
    $input['password'] = Hash::make($input['password']);
    // Remove confirm-password from input if needed, but User::create handles fillable

    $user = User::create($input);

    // Sync roles using Spatie
    $user->assignRole($request->input('roles'));

    // Update legacy role column for backward compatibility (optional)
    if (is_array($request->input('roles'))) {
      $user->role = $request->input('roles')[0];
      $user->save();
    }

    return redirect()->route('superadmin.users.index')
      ->with('success', 'User berhasil dibuat');
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    $user = User::find($id);
    return view('backend.superadmin.users.show', compact('user'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $user = User::find($id);
    $roles = Role::pluck('name', 'name')->all();
    $userRole = $user->roles->pluck('name', 'name')->all();

    return view('backend.superadmin.users.edit', compact('user', 'roles', 'userRole'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'name' => 'required',
      'email' => 'required|email|unique:users,email,' . $id,
      'password' => 'same:confirm-password',
      'roles' => 'required'
    ]);

    $input = $request->all();
    if (!empty($input['password'])) {
      $input['password'] = Hash::make($input['password']);
    } else {
      $input = \Illuminate\Support\Arr::except($input, array('password'));
    }

    $user = User::find($id);
    $user->update($input);

    // Sync roles
    $user->syncRoles($request->input('roles'));

    // Update legacy role
    if (is_array($request->input('roles'))) {
      $user->role = $request->input('roles')[0];
      $user->save();
    }

    return redirect()->route('superadmin.users.index')
      ->with('success', 'User berhasil diperbarui');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    if ($id == 1) { // Protect Super Admin
      return redirect()->route('superadmin.users.index')->with('error', 'User Super Admin Utama tidak bisa dihapus.');
    }

    User::find($id)->delete();
    return redirect()->route('superadmin.users.index')
      ->with('success', 'User berhasil dihapus');
  }
}
