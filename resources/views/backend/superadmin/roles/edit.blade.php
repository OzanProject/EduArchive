@extends('backend.layouts.app')

@section('title', 'Edit Role')
@section('page_title', 'Edit Role: ' . $role->name)

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.roles.index') }}">Roles</a></li>
  <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-warning">
        <div class="card-header">
          <h3 class="card-title">Edit Role</h3>
        </div>
        <form action="{{ route('superadmin.roles.update', $role->id) }}" method="POST">
          @csrf
          @method('PATCH')
          <div class="card-body">
            <div class="form-group">
              <label for="name">Nama Role</label>
              <input type="text" name="name" class="form-control" id="name" value="{{ $role->name }}" required>
            </div>

            <div class="form-group">
              <label>Permissions (Hak Akses)</label>
              <div class="row">
                @foreach($permissions as $value)
                  <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                      <input class="custom-control-input" type="checkbox" id="perm_{{ $value->id }}" name="permission[]"
                        value="{{ $value->id }}" {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                      <label for="perm_{{ $value->id }}"
                        class="custom-control-label font-weight-normal">{{ $value->name }}</label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Role</button>
            <a href="{{ route('superadmin.roles.index') }}" class="btn btn-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection