@extends('backend.layouts.app')

@section('title', 'Buat User Baru')
@section('page_title', 'Create User')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.users.index') }}">Users</a></li>
  <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Form User Baru</h3>
        </div>
        <form action="{{ route('superadmin.users.store') }}" method="POST">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="name">Nama Lengkap</label>
              <input type="text" name="name" class="form-control" id="name" placeholder="Nama Lengkap" required>
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
            </div>

            <div class="form-group">
              <label for="confirm-password">Konfirmasi Password</label>
              <input type="password" name="confirm-password" class="form-control" id="confirm-password"
                placeholder="Ulangi Password" required>
            </div>

            <div class="form-group">
              <label>Role</label>
              <select class="form-control" name="roles[]" multiple required>
                @foreach($roles as $role)
                  <option value="{{ $role }}">{{ $role }}</option>
                @endforeach
              </select>
              <small class="text-muted">Tekan CTRL untuk memilih lebih dari satu role.</small>
            </div>

          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan User</button>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection