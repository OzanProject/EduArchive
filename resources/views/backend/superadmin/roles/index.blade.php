@extends('backend.layouts.app')

@section('title', 'Manajemen Role')
@section('page_title', 'Roles & Permissions')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Roles</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Role</h3>
          <div class="card-tools">
            <a href="{{ route('superadmin.roles.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Buat Role Baru
            </a>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Role</th>
                <th>Permissions</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($roles as $key => $role)
                <tr>
                  <td>{{ $roles->firstItem() + $key }}</td>
                  <td>{{ $role->name }}</td>
                  <td>
                    @foreach($role->permissions->take(5) as $perm)
                      <span class="badge badge-info">{{ $perm->name }}</span>
                    @endforeach
                    @if($role->permissions->count() > 5)
                      <span class="badge badge-secondary">+{{ $role->permissions->count() - 5 }} More</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('superadmin.roles.show', $role->id) }}" class="btn btn-info btn-sm" title="Lihat"><i
                        class="fas fa-eye"></i></a>
                    <a href="{{ route('superadmin.roles.edit', $role->id) }}" class="btn btn-warning btn-sm" title="Edit"><i
                        class="fas fa-edit"></i></a>

                    @if($role->name != 'superadmin')
                      <form action="{{ route('superadmin.roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                          onclick="return confirm('Yakin ingin menghapus role ini?')"><i class="fas fa-trash"></i></button>
                      </form>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          {{ $roles->links() }}
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>
@endsection