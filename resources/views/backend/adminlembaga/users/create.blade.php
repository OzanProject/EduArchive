@extends('backend.layouts.app')

@section('title', 'Tambah Operator')
@section('page_title', 'Tambah Operator')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.users.index') }}">Operator</a></li>
  <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-6">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Form Tambah Operator</h3>
        </div>
        <form action="{{ route('adminlembaga.users.store') }}" method="POST">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="name">Nama Lengkap</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
                placeholder="Masukkan nama operator" value="{{ old('name') }}" required>
              @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email"
                placeholder="Masukkan email" value="{{ old('email') }}" required>
              @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                id="password" placeholder="Password" required>
              @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group">
              <label for="password_confirmation">Konfirmasi Password</label>
              <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                placeholder="Ulangi Password" required>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('adminlembaga.users.index') }}" class="btn btn-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection