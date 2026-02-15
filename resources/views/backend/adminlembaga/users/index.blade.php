@extends('backend.layouts.app')

@section('title', 'Manajemen Operator')
@section('page_title', 'Manajemen Operator')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Operator</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Operator Sekolah</h3>
          <div class="card-tools">
            <a href="{{ route('adminlembaga.users.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Tambah Operator
            </a>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="width: 50px">No</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Terakhir Login</th>
                  <th style="width: 150px">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($users as $user)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                      @if($user->last_login)
                        {{ \Carbon\Carbon::parse($user->last_login)->diffForHumans() }}
                      @else
                        <span class="text-muted">Belum pernah login</span>
                      @endif
                    </td>
                    <td>
                      <a href="{{ route('adminlembaga.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="{{ route('adminlembaga.users.destroy', $user->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus operator ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center">Belum ada operator.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="mt-3">
            {{ $users->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection