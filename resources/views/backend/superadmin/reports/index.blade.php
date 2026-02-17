@extends('backend.layouts.app')

@section('title', 'Laporan Sekolah')
@section('page_title', 'Laporan Statistik Sekolah')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Laporan Sekolah</li>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-search mr-1"></i> Cari Berdasarkan Nama</h3>
        </div>
        <form action="{{ route('superadmin.reports.index') }}" method="GET">
          <div class="card-body">
            <div class="form-group">
              <label for="tenant_id">Pilih Sekolah</label>
              <select name="tenant_id" id="tenant_id" class="form-control select2" required>
                <option value="">-- Pilih Sekolah --</option>
                @foreach($tenants as $tenant)
                  <option value="{{ $tenant->id }}">{{ $tenant->nama_sekolah ?? $tenant->id }}</option>
                @endforeach
              </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fas fa-chart-pie mr-1"></i> Tampilkan Laporan
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card card-success card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-id-card mr-1"></i> Cari Berdasarkan NPSN</h3>
        </div>
        <form action="{{ route('superadmin.reports.index') }}" method="GET">
          <div class="card-body">
            <div class="form-group">
              <label for="npsn">Input NPSN</label>
              <div class="input-group">
                <input type="text" name="npsn" class="form-control" placeholder="Contoh: 12345678" value="{{ old('npsn') }}" required>
                <span class="input-group-append">
                  <button type="submit" class="btn btn-success btn-flat"><i class="fas fa-search"></i> Cari</button>
                </span>
              </div>
            </div>
            <p class="text-muted small mt-2">Masukan NPSN sekolah untuk langsung melihat laporan statistik.</p>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(function () {
      $('.select2').select2({
        theme: 'bootstrap4'
      });
    });
  </script>
@endpush