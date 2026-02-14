@extends('backend.layouts.app')

@section('title', 'Buat Role Baru')
@section('page_title', 'Create Role')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.roles.index') }}">Roles</a></li>
  <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Form Role Baru</h3>
        </div>
        <form action="{{ route('superadmin.roles.store') }}" method="POST">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="name">Nama Role</label>
              <input type="text" name="name" class="form-control" id="name" placeholder="Contoh: Supervisor" required>
            </div>

            <div class="form-group">
              <label>Permissions (Hak Akses)</label>
              <div class="row">
                @foreach($permissions as $value)
                  <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                      <input class="custom-control-input" type="checkbox" id="perm_{{ $value->id }}" name="permission[]"
                        value="{{ $value->id }}">
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
            <button type="submit" class="btn btn-primary">Simpan Role</button>
            <a href="{{ route('superadmin.roles.index') }}" class="btn btn-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection