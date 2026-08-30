@extends('backend.layouts.app')

@section('title', 'Monitoring Sekolah')
@section('page_title')
  Monitoring Data {{ $category == 'graduates' ? 'Lulusan' : 'Siswa' }}
@endsection

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Monitoring {{ $category == 'graduates' ? 'Lulusan' : 'Siswa' }}</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center" style="gap: 15px;">
            <h3 class="card-title mb-2 mb-md-0">Pilih Sekolah untuk Monitoring {{ $category == 'graduates' ? 'Lulusan' : 'Siswa' }}
            </h3>
            <div class="card-tools d-flex flex-wrap align-items-center justify-content-md-end" style="gap: 10px;">
              <div class="d-flex flex-wrap align-items-center" style="gap: 5px;">
                  <a href="{{ route('superadmin.monitoring.export_all_excel', request()->all()) }}" class="btn btn-success btn-sm">
                      <i class="fas fa-file-excel mr-1"></i> Export Excel
                  </a>
                  <a href="{{ route('superadmin.monitoring.export_all_pdf', request()->all()) }}" class="btn btn-danger btn-sm">
                      <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                  </a>
              </div>
              <form action="{{ route('superadmin.monitoring.index') }}" method="GET" class="d-flex flex-wrap align-items-center m-0" style="gap: 10px;">
                <input type="hidden" name="category" value="{{ $category }}">
                
                <div class="input-group input-group-sm" style="max-width: 120px;">
                  <select name="per_page" class="form-control" onchange="this.form.submit()">
                    <option value="10" {{ (isset($per_page) && $per_page == 10) ? 'selected' : '' }}>10 Baris</option>
                    <option value="20" {{ (isset($per_page) && $per_page == 20) ? 'selected' : '' }}>20 Baris</option>
                    <option value="50" {{ (isset($per_page) && $per_page == 50) ? 'selected' : '' }}>50 Baris</option>
                    <option value="100" {{ (isset($per_page) && $per_page == 100) ? 'selected' : '' }}>100 Baris</option>
                  </select>
                </div>

                <div class="input-group input-group-sm" style="max-width: 150px;">
                  <select name="age_filter" class="form-control" onchange="this.form.submit()">
                    <option value="">Semua Usia</option>
                    <option value="under_25" {{ $age_filter == 'under_25' ? 'selected' : '' }}>Usia < 25 Tahun</option>
                    <option value="over_25" {{ $age_filter == 'over_25' ? 'selected' : '' }}>Usia ≥ 25 Tahun</option>
                  </select>
                </div>

                <div class="input-group input-group-sm" style="width: 200px;">
                  <input type="text" name="table_search" class="form-control float-right"
                    placeholder="Cari Sekolah..." value="{{ request('table_search') }}">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </form>

              <a href="{{ route('superadmin.monitoring.print_all', request()->query()) }}" target="_blank" class="btn btn-warning btn-sm d-flex align-items-center">
                <i class="fas fa-print mr-1"></i> Cetak Semua
              </a>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th style="width: 50px;">No.</th>
                <th>NPSN</th>
                <th>Nama Sekolah</th>
                <th>Total {{ $category == 'graduates' ? 'Lulusan' : 'Siswa' }}</th>
                <th>L</th>
                <th>P</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($tenants as $tenant)
                <tr>
                  <td>{{ $loop->iteration + $tenants->firstItem() - 1 }}</td>
                  <td>{{ $tenant->npsn }}</td>
                  <td style="white-space: normal; min-width: 200px;">
                    <strong>{{ $tenant->nama_sekolah }}</strong><br>
                    <small class="text-muted">{{ $tenant->jenjang }}</small>
                  </td>
                  <td class="font-weight-bold">{{ $tenant->stats_total ?? 0 }}</td>
                  <td class="text-info">{{ $tenant->stats_l ?? 0 }}</td>
                  <td class="text-pink" style="color: #e83e8c;">{{ $tenant->stats_p ?? 0 }}</td>
                  <td>
                    @if($tenant->status_aktif)
                      <span class="badge badge-success">Aktif</span>
                    @else
                      <span class="badge badge-danger">Suspended</span>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex flex-wrap" style="gap: 5px;">
                      <a href="{{ route('superadmin.monitoring.school', ['id' => $tenant->id, 'status' => ($category == 'graduates' ? 'lulus' : 'aktif'), 'age_filter' => $age_filter]) }}"
                        class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Lihat {{ $category == 'graduates' ? 'Lulusan' : 'Siswa' }}
                      </a>
                      <a href="{{ route('superadmin.monitoring.print_recap', ['id' => $tenant->id, 'status' => ($category == 'graduates' ? 'lulus' : 'aktif'), 'age_filter' => $age_filter]) }}"
                        target="_blank" class="btn btn-warning btn-sm">
                        <i class="fas fa-print"></i> Cetak Rekap
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-4">Tidak ada data sekolah.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          {{ $tenants->links('pagination::bootstrap-4') }}
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>
@endsection