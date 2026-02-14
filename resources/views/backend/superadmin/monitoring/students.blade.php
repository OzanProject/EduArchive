@extends('backend.layouts.app')

@section('title', 'Data Siswa')
@section('page_title', 'Data Siswa - ' . $tenant->nama_sekolah)

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.monitoring.index') }}">Monitoring</a></li>
  <li class="breadcrumb-item active">{{ $tenant->nama_sekolah }}</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar {{ request('status') == 'lulus' ? 'Alumni' : 'Siswa Aktif' }} di
              {{ $tenant->nama_sekolah }}
            </h3>
            <div class="card-tools d-flex">
              <form action="{{ route('superadmin.monitoring.school', $tenant->id) }}" method="GET"
                class="form-inline mr-2">
                <input type="hidden" name="status" value="{{ request('status', 'aktif') }}">

                <div class="input-group input-group-sm mr-2" style="width: 200px;">
                  <input type="text" name="table_search" class="form-control float-right" placeholder="Cari Siswa..."
                    value="{{ request('table_search') }}">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>

                @if(request('status') == 'lulus')
                  <div class="input-group input-group-sm mr-2">
                    <select name="year" class="form-control" onchange="this.form.submit()">
                      <option value="">Semua Tahun</option>
                      @foreach($graduation_years as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                      @endforeach
                    </select>
                  </div>
                @endif
              </form>

              <div class="btn-group btn-group-sm mr-2">
                <a href="{{ route('superadmin.monitoring.print_recap', ['id' => $tenant->id, 'status' => request('status', 'aktif'), 'year' => request('year')]) }}"
                  target="_blank" class="btn btn-warning btn-sm">
                  <i class="fas fa-print"></i> Cetak Rekap
                </a>
              </div>

              <a href="{{ route('superadmin.monitoring.index', ['category' => request('status') == 'lulus' ? 'graduates' : 'students']) }}"
                class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
              </a>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($students as $student)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $student->nisn }}</td>
                  <td>{{ $student->nama }}</td>
                  <td>{{ $student->kelas }}</td>
                  <td>
                    <a href="{{ route('superadmin.monitoring.student', ['tenant_id' => $tenant->id, 'nisn' => $student->nisn]) }}"
                      class="btn btn-info btn-sm" title="Detail Siswa & Dokumen">
                      <i class="fas fa-search"></i> Detail
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Tidak ada data siswa ditemukan (Dummy Data).</td>
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