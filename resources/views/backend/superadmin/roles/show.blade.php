@extends('backend.layouts.app')

@section('title', 'Detail Role')
@section('page_title', 'Detail Role')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.roles.index') }}">Role Management</a></li>
  <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Informasi Role</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label>Nama Role:</label>
            <p class="form-control-static">{{ $role->name }}</p>
          </div>
          <div class="form-group">
             <label>Permissions:</label>
             <div class="row">
                @if(!empty($rolePermissions))
                    @foreach($rolePermissions as $v)
                        <div class="col-md-3">
                            <span class="badge badge-success mb-2">{{ $v->name }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted col-12">Tidak ada permission.</p>
                @endif
             </div>
          </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('superadmin.roles.index') }}" class="btn btn-default">Kembali</a>
          <a href="{{ route('superadmin.roles.edit', $role->id) }}" class="btn btn-warning float-right">Edit Role</a>
        </div>
      </div>
    </div>
  </div>
@endsection
