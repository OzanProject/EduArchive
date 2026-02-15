@extends('backend.layouts.app')

@section('title', 'Edit Operator')
@section('page_title', 'Edit Operator')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.users.index') }}">Operator</a></li>
  <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-6">
      <div class="card card-warning">
        <div class="card-header">
          <h3 class="card-title">Form Edit Operator</h3>
        </div>
        <form action="{{ route('adminlembaga.users.update', $user->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="form-group">
              <label for="name">Nama Lengkap</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
                value="{{ old('name', $user->name) }}" required>
              @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email"
                value="{{ old('email', $user->email) }}" required>
              @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
            <hr>
            <div class="form-group">
              <label for="password">Password Baru <small class="text-muted">(Kosongkan jika tidak ingin
                  mengubah)</small></label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                id="password" placeholder="Password Baru">
              @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group">
              <label for="password_confirmation">Konfirmasi Password Baru</label>
              <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                placeholder="Ulangi Password Baru">
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('adminlembaga.users.index') }}" class="btn btn-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection