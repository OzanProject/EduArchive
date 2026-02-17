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
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Pilih Sekolah</h3>
        </div>
        <form action="{{ route('superadmin.reports.index') }}" method="GET">
          <div class="card-body">
            <div class="form-group">
              <label for="tenant_id">Sekolah</label>
              <select name="tenant_id" id="tenant_id" class="form-control select2" required>
                <option value="">-- Pilih Sekolah --</option>
                @foreach($tenants as $tenant)
                  <option value="{{ $tenant->id }}">{{ $tenant->nama_sekolah ?? $tenant->id }} ({{ $tenant->id }})</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fas fa-chart-pie"></i> Tampilkan Laporan
            </button>
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