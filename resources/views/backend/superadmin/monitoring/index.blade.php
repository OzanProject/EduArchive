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
          <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title">Pilih Sekolah untuk Monitoring {{ $category == 'graduates' ? 'Lulusan' : 'Siswa' }}
            </h3>
            <div class="card-tools">
              <form action="{{ route('superadmin.monitoring.index') }}" method="GET">
                <input type="hidden" name="category" value="{{ $category }}">
                <div class="input-group input-group-sm" style="width: 450px;">
                  <select name="age_filter" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">Semua Usia</option>
                    <option value="under_25" {{ $age_filter == 'under_25' ? 'selected' : '' }}>Usia < 25 Tahun</option>
                    <option value="over_25" {{ $age_filter == 'over_25' ? 'selected' : '' }}>Usia ≥ 25 Tahun</option>
                  </select>
                  <input type="text" name="table_search" class="form-control float-right"
                    placeholder="Cari Sekolah (NPSN/Nama)..." value="{{ request('table_search') }}">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>NPSN</th>
                <th>Nama Sekolah</th>
                <th>Jenjang</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($tenants as $tenant)
                <tr>
                  <td>{{ $tenant->npsn }}</td>
                  <td>{{ $tenant->nama_sekolah }}</td>
                  <td>{{ $tenant->jenjang }}</td>
                  <td>
                    @if($tenant->status_aktif)
                      <span class="badge badge-success">Aktif</span>
                    @else
                      <span class="badge badge-danger">Suspended</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('superadmin.monitoring.school', ['id' => $tenant->id, 'status' => ($category == 'graduates' ? 'lulus' : 'aktif'), 'age_filter' => $age_filter]) }}"
                      class="btn btn-info btn-sm">
                      <i class="fas fa-eye"></i> Lihat {{ $category == 'graduates' ? 'Lulusan' : 'Siswa' }}
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Tidak ada data sekolah.</td>
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