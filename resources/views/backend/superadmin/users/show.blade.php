@extends('backend.layouts.app')

@section('title', 'Detail User')
@section('page_title', 'Detail User')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.users.index') }}">User Management</a></li>
  <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-6">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Informasi User</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label>Nama:</label>
            <p class="form-control-static">{{ $user->name }}</p>
          </div>
          <div class="form-group">
            <label>Email:</label>
            <p class="form-control-static">{{ $user->email }}</p>
          </div>
          <div class="form-group">
            <label>Role:</label>
            <div>
              @foreach($user->getRoleNames() as $role)
                <span class="badge badge-success">{{ $role }}</span>
              @endforeach
            </div>
          </div>
          <div class="form-group">
            <label>Tenant ID (Jika ada):</label>
            <p class="form-control-static">{{ $user->tenant_id ?? '-' }}</p>
          </div>
          <div class="form-group">
            <label>Dibuat Pada:</label>
            <p class="form-control-static">{{ $user->created_at->format('d M Y H:i') }}</p>
          </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('superadmin.users.index') }}" class="btn btn-default">Kembali</a>
          <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn btn-warning float-right">Edit User</a>
        </div>
      </div>
    </div>
  </div>
@endsection