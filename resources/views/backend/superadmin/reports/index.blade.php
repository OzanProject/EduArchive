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

      <div class="card card-warning card-outline mt-3">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-globe mr-1"></i> Data Seluruh Lembaga</h3>
        </div>
        <div class="card-body text-center">
          <p class="text-muted small">Melihat gabungan total statistik siswa, guru, sarpras, dll dari semua sekolah.</p>
          <a href="{{ route('superadmin.reports.show', 'all') }}" class="btn btn-warning btn-block">
            <i class="fas fa-layer-group mr-1"></i> Tampilkan Semua Lembaga
          </a>
        </div>
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

  <div class="row mt-4">
    <div class="col-12">
      <div class="card card-secondary card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-list mr-1"></i> Daftar Semua Sekolah</h3>
        </div>
        <div class="card-body">
          <form action="{{ route('superadmin.reports.index') }}" method="GET" class="mb-3">
            <div class="row">
              <div class="col-md-4 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Cari Nama / NPSN..." value="{{ request('search') }}">
              </div>
              <div class="col-md-3 mb-2">
                <select name="jenjang" class="form-control" onchange="this.form.submit()">
                  <option value="">-- Semua Jenjang --</option>
                  <option value="SD" {{ request('jenjang') == 'SD' ? 'selected' : '' }}>SD</option>
                  <option value="SMP" {{ request('jenjang') == 'SMP' ? 'selected' : '' }}>SMP</option>
                  <option value="SMA" {{ request('jenjang') == 'SMA' ? 'selected' : '' }}>SMA</option>
                  <option value="SMK" {{ request('jenjang') == 'SMK' ? 'selected' : '' }}>SMK</option>
                </select>
              </div>
              <div class="col-md-3 mb-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                @if(request('search') || request('jenjang'))
                  <a href="{{ route('superadmin.reports.index') }}" class="btn btn-default"><i class="fas fa-sync"></i> Reset</a>
                @endif
              </div>
            </div>
          </form>

          <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
              <thead class="bg-light">
                <tr>
                  <th style="width: 50px;">No</th>
                  <th>NPSN</th>
                  <th>Nama Sekolah</th>
                  <th>Jenjang</th>
                  <th>Status</th>
                  <th style="width: 150px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($allTenants as $t)
                  <tr>
                    <td>{{ $loop->iteration + ($allTenants->currentPage() - 1) * $allTenants->perPage() }}</td>
                    <td>{{ $t->npsn ?? '-' }}</td>
                    <td>{{ $t->nama_sekolah ?? $t->id }}</td>
                    <td>{{ $t->jenjang ?? '-' }}</td>
                    <td>
                      @if($t->status_aktif)
                        <span class="badge badge-success">Aktif</span>
                      @else
                        <span class="badge badge-danger">Nonaktif</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <a href="{{ route('superadmin.reports.show', $t->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-chart-bar"></i> Lihat Laporan
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center">Belum ada data sekolah yang sesuai kriteria.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          
          <div class="mt-3">
            {{ $allTenants->links() }}
          </div>
        </div>
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