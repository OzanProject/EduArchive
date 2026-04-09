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
              <label class="d-block mb-3">Permissions (Hak Akses)</label>
              <div class="row">
                @foreach($permissionGroups as $group => $permissionsList)
                  <div class="col-md-4 mb-3">
                    <div class="card card-outline card-secondary h-100 shadow-sm border-top-0">
                      <div class="card-header bg-light py-2">
                        <h3 class="card-title text-capitalize font-weight-bold" style="font-size: 1rem;">
                          <i class="fas fa-layer-group text-secondary mr-1"></i> Modul {{ Str::replace('-', ' ', $group) }}
                        </h3>
                      </div>
                      <div class="card-body py-2">
                        @foreach($permissionsList as $value)
                          <div class="custom-control custom-checkbox mb-2">
                            <input class="custom-control-input" type="checkbox" id="perm_{{ $value->id }}" name="permission[]"
                              value="{{ $value->id }}">
                            <label for="perm_{{ $value->id }}"
                              class="custom-control-label font-weight-normal">{{ $value->name }}</label>
                          </div>
                        @endforeach
                      </div>
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