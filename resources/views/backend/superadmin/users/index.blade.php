@extends('backend.layouts.app')

@section('title', 'Manajemen User')
@section('page_title', 'User Management')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Pengguna</h3>
          <div class="card-tools">
            <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Tambah User Baru
            </a>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $key => $user)
                <tr>
                  <td>{{ $users->firstItem() + $key }}</td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>
                    @if(!empty($user->getRoleNames()))
                      @foreach($user->getRoleNames() as $v)
                        <span class="badge badge-success">{{ $v }}</span>
                      @endforeach
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('superadmin.users.show', $user->id) }}" class="btn btn-info btn-sm" title="Lihat"><i
                        class="fas fa-eye"></i></a>
                    <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn btn-warning btn-sm" title="Edit"><i
                        class="fas fa-edit"></i></a>

                    @if($user->id != 1)
                      <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                          onclick="return confirm('Yakin ingin menghapus user ini?')"><i class="fas fa-trash"></i></button>
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
          {{ $users->links() }}
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>
@endsection