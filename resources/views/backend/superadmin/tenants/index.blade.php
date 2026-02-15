@extends('backend.layouts.app')

@section('title', 'Data Sekolah')
@section('page_title', 'Master Data Sekolah')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Sekolah</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Sekolah Terdaftar</h3>
          <div class="card-tools d-flex">
            <form action="{{ route('superadmin.tenants.index') }}" method="GET" class="form-inline mr-2">
              <div class="input-group input-group-sm" style="width: 200px;">
                <input type="text" name="table_search" class="form-control float-right" placeholder="Cari Sekolah..."
                  value="{{ request('table_search') }}">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-default">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
            <a href="{{ route('superadmin.tenants.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Tambah Sekolah
            </a>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>ID/NPSN</th>
                <th>Nama Sekolah</th>
                <th>Jenjang</th>
                <th>Domain</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($tenants as $tenant)
                <tr>
                  <td>
                    <b>{{ $tenant->npsn }}</b><br>
                    <small class="text-muted">{{ $tenant->id }}</small>
                  </td>
                  <td>{{ $tenant->nama_sekolah }}</td>
                  <td><span class="badge badge-info">{{ $tenant->jenjang }}</span></td>
                  <td>
                    @php
                      $url = url($tenant->id);
                    @endphp
                    <a href="{{ $url }}" target="_blank" class="text-bold">
                      {{ $url }} <i class="fas fa-external-link-alt text-xs ml-1"></i>
                    </a>
                    <br>
                    <a href="{{ $url }}/adminlembaga/dashboard" target="_blank" class="text-muted text-xs">
                      <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                  </td>
                  <td>
                    @if($tenant->status_aktif)
                      <span class="badge badge-success">Aktif</span>
                    @else
                      <span class="badge badge-warning">Non-Aktif (Pending)</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('superadmin.tenants.edit', $tenant->id) }}" class="btn btn-warning btn-sm"
                      title="Edit"><i class="fas fa-edit"></i></a>

                    @if($tenant->status_aktif)
                      <form action="{{ route('superadmin.tenants.status', $tenant->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="suspended">
                        <button type="submit" class="btn btn-secondary btn-sm" title="Suspend Sekolah"
                          onclick="return confirm('Suspend sekolah ini? Akses akan dibekukan.')">
                          <i class="fas fa-ban"></i>
                        </button>
                      </form>
                    @else
                      <form action="{{ route('superadmin.tenants.status', $tenant->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="btn btn-success btn-sm" title="Aktifkan Sekolah"
                          onclick="return confirm('Aktifkan kembali sekolah ini?')">
                          <i class="fas fa-check"></i>
                        </button>
                      </form>
                    @endif

                    <form action="{{ route('superadmin.tenants.destroy', $tenant->id) }}" method="POST"
                      style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm" title="Hapus Permanen"
                        onclick="return confirm('Hapus sekolah ini? Data akan hilang permanen!')"><i
                          class="fas fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center">Belum ada data sekolah.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>
@endsection