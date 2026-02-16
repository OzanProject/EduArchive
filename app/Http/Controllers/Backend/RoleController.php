<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $roles = Role::orderByRaw("CASE WHEN name = 'superadmin' THEN 0 ELSE 1 END, id DESC")->paginate(10);
    return view('backend.superadmin.roles.index', compact('roles'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $permissions = Permission::get();
    return view('backend.superadmin.roles.create', compact('permissions'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|unique:roles,name',
      'permission' => 'nullable|array',
    ]);

    $role = Role::create(['name' => $request->input('name')]);

    $permissions = $request->input('permission') ? Permission::whereIn('id', $request->input('permission'))->get() : [];
    $role->syncPermissions($permissions);

    return redirect()->route('superadmin.roles.index')
      ->with('success', 'Role berhasil dibuat');
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    $role = Role::findOrFail($id);
    $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
      ->where("role_has_permissions.role_id", $id)
      ->get();

    return view('backend.superadmin.roles.show', compact('role', 'rolePermissions'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $role = Role::findOrFail($id);
    $permissions = Permission::get();
    $rolePermissions = \DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
      ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
      ->all();

    return view('backend.superadmin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    $request->validate([
      'name' => 'required|unique:roles,name,' . $id,
      'permission' => 'nullable|array',
    ]);

    /** @var Role $role */
    $role = Role::findOrFail($id);
    $role->name = $request->input('name');
    $role->save();

    $permissions = $request->input('permission') ? Permission::whereIn('id', $request->input('permission'))->get() : [];
    $role->syncPermissions($permissions);

    return redirect()->route('superadmin.roles.index')
      ->with('success', 'Role berhasil diperbarui');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    /** @var Role $role */
    $role = Role::findOrFail($id);

    if ($role->name == 'superadmin') { // Prevent deleting Super Admin role
      return redirect()->route('superadmin.roles.index')->with('error', 'Tidak bisa menghapus Role Utama.');
    }

    $role->delete();

    return redirect()->route('superadmin.roles.index')
      ->with('success', 'Role berhasil dihapus');
  }
}
