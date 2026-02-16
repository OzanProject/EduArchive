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
        <div class="card-body">
          <form action="{{ route('superadmin.tenants.bulk_action') }}" method="POST" id="bulkActionForm">
            @csrf
            <div class="mb-3 d-flex align-items-center">
              <select name="action" class="form-control form-control-sm mr-2" style="width: 200px;" required>
                <option value="">-- Pilih Aksi Massal --</option>
                <option value="activate">Aktifkan Terpilih</option>
                <option value="suspend">Suspend Terpilih</option>
                <option value="delete">Hapus Permanen Terpilih</option>
              </select>
              <button type="submit" class="btn btn-primary btn-sm"
                onclick="return confirm('Apakah Anda yakin ingin melakukan aksi massal ini?')">Terapkan</button>
            </div>
          </form>

          <div class="table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th style="width: 50px;">
                    <div class="custom-control custom-checkbox">
                      <input class="custom-control-input" type="checkbox" id="selectAll">
                      <label for="selectAll" class="custom-control-label"></label>
                    </div>
                  </th>
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
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input tenant-checkbox" type="checkbox" name="ids[]"
                          id="tenant{{ $tenant->id }}" value="{{ $tenant->id }}" form="bulkActionForm">
                        <label for="tenant{{ $tenant->id }}" class="custom-control-label"></label>
                      </div>
                    </td>
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
                    <td colspan="7" class="text-center">Belum ada data sekolah.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function () {
      // Select All Checkbox - Click Event
      $('#selectAll').click(function () {
        $('.tenant-checkbox').prop('checked', this.checked);
      });

      // Individual Checkbox - Change Event
      $('.tenant-checkbox').change(function () {
        // If all checkboxes are checked, check Select All
        if ($('.tenant-checkbox:checked').length == $('.tenant-checkbox').length) {
          $('#selectAll').prop('checked', true);
        } else {
          $('#selectAll').prop('checked', false);
        }
      });
    });
  </script>
@endpush