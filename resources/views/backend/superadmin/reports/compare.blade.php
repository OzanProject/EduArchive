@extends('backend.layouts.app')

@section('title', 'Perbandingan Laporan Lembaga')
@section('page_title', 'Perbandingan Statistik Antar Sekolah')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.reports.index') }}">Laporan Sekolah</a></li>
  <li class="breadcrumb-item active">Perbandingan Lembaga</li>
@endsection

@section('content')
  <div class="row">
    <!-- Action buttons -->
    <div class="col-12 mb-3 d-flex flex-wrap justify-content-end align-items-center no-print" style="gap: 10px;">
        <form action="{{ route('superadmin.reports.show', 'all') }}" method="GET" class="m-0">
            <select name="sort" class="form-control" onchange="this.form.submit()" style="min-width: 220px;">
                <option value="nama_asc" {{ (isset($sort) && $sort == 'nama_asc') ? 'selected' : '' }}>Urutkan: Nama Sekolah (A-Z)</option>
                <option value="students_desc" {{ (isset($sort) && $sort == 'students_desc') ? 'selected' : '' }}>Urutkan: Siswa Terbanyak</option>
                <option value="students_asc" {{ (isset($sort) && $sort == 'students_asc') ? 'selected' : '' }}>Urutkan: Siswa Paling Sedikit</option>
            </select>
        </form>
        <a href="{{ route('superadmin.reports.pdf', ['report' => 'all', 'sort' => isset($sort) ? $sort : 'nama_asc']) }}" class="btn btn-warning" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF Komparasi
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Halaman
        </button>
    </div>

    <!-- Comparative Charts -->
    <div class="col-md-6">
      <div class="card card-primary card-outline h-100">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-user-graduate mr-1"></i> Perbandingan Jumlah Siswa Aktif</h3>
        </div>
        <div class="card-body">
          <canvas id="studentCompareChart" style="min-height: 250px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card card-warning card-outline h-100">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-chalkboard-teacher mr-1"></i> Perbandingan Jumlah Guru</h3>
        </div>
        <div class="card-body">
          <canvas id="teacherCompareChart" style="min-height: 250px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-12">
      <div class="card card-secondary card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-table mr-1"></i> Rekapitulasi Rinci Antar Sekolah</h3>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-hover table-bordered table-striped text-nowrap">
            <thead class="bg-light">
              <tr>
                <th rowspan="2" class="align-middle text-center" style="width: 50px;">No</th>
                <th rowspan="2" class="align-middle">Nama Sekolah</th>
                <th rowspan="2" class="align-middle text-center">Jenjang</th>
                <th colspan="2" class="text-center bg-primary text-white">Siswa</th>
                <th colspan="{{ count($docTypes) }}" class="text-center bg-secondary text-white">Upload Dokumen</th>
                <th rowspan="2" class="align-middle text-center bg-warning">Total Guru</th>
                <th rowspan="2" class="align-middle text-center bg-info text-white">Act. Pembelajaran</th>
                <th rowspan="2" class="align-middle text-center bg-purple text-white">Usulan Sarpras (Pending)</th>
                <th rowspan="2" class="align-middle text-center bg-success text-white">Total PIP</th>
                <th rowspan="2" class="align-middle text-center">Aksi</th>
              </tr>
              <tr>
                 <th class="text-center bg-primary text-white" style="border-top:1px solid #fff">Aktif</th>
                 <th class="text-center bg-primary text-white" style="border-top:1px solid #fff">Lulusan</th>
                 @foreach($docTypes as $type)
                   <th class="text-center bg-secondary text-white" style="border-top:1px solid #fff">{{ $type }}</th>
                 @endforeach
              </tr>
            </thead>
            <tbody>
              @foreach($stats as $index => $row)
                <tr>
                  <td class="text-center">{{ $index + 1 }}</td>
                  <td class="font-weight-bold">{{ $row['nama_sekolah'] }} <br><small class="text-muted">NPSN: {{ $row['npsn'] }}</small></td>
                  <td class="text-center">{{ $row['jenjang'] }}</td>
                  <td class="text-center text-primary font-weight-bold">{{ $row['active_students'] }}</td>
                  <td class="text-center">{{ $row['graduated_students'] }}</td>
                  @foreach($docTypes as $type)
                    <td class="text-center">{{ $row['documents'][$type] ?? 0 }}</td>
                  @endforeach
                  <td class="text-center text-warning font-weight-bold">{{ $row['total_teachers'] }}</td>
                  <td class="text-center">{{ $row['total_learning'] }}</td>
                  <td class="text-center text-danger">{{ $row['pending_infrastructure'] }}</td>
                  <td class="text-center font-weight-bold text-success">{{ $row['total_pip'] ?? 0 }}</td>
                  <td class="text-center">
                      <a href="{{ route('superadmin.reports.show', $row['id']) }}" class="btn btn-sm btn-info">
                          <i class="fas fa-eye"></i> Detail
                      </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
    <style>
      @media print {
        /* Hide everything by default */
        body * {
          visibility: hidden;
        }

        /* Unhide the content wrapper and its children */
        .content-wrapper,
        .content-wrapper * {
          visibility: visible;
        }

        /* Reset position to top-left */
        .content-wrapper {
          position: absolute;
          left: 0;
          top: 0;
          width: 100% !important;
          margin: 0 !important;
          padding: 0 !important;
          background-color: white !important;
        }

        .main-sidebar,
        .main-header,
        .main-footer,
        .no-print,
        .card-header .card-tools,
        .btn,
        .breadcrumb {
          display: none !important;
        }

        .card {
          border: none !important;
          box-shadow: none !important;
        }

        .table-responsive {
            overflow: visible !important;
        }
      }
    </style>
@endpush

@push('scripts')
  <script src="{{ asset('adminlte3/plugins/chart.js/Chart.min.js') }}"></script>
  <script>
    $(function () {
      const labels = {!! json_encode($stats->pluck('nama_sekolah')) !!};
      const activeStudentsData = {!! json_encode($stats->pluck('active_students')) !!};
      const totalTeachersData = {!! json_encode($stats->pluck('total_teachers')) !!};

      // 1. Students Compare Bar Chart
      new Chart($('#studentCompareChart').get(0).getContext('2d'), {
        type: 'horizontalBar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Jumlah Siswa Aktif',
            data: activeStudentsData,
            backgroundColor: '#007bff',
            borderColor: '#007bff',
            borderWidth: 1
          }]
        },
        options: {
          maintainAspectRatio: false,
          responsive: true,
          legend: { display: false },
          scales: {
            xAxes: [{ ticks: { beginAtZero: true, stepSize: 5 } }]
          }
        }
      });

      // 2. Teachers Compare Bar Chart
      new Chart($('#teacherCompareChart').get(0).getContext('2d'), {
        type: 'horizontalBar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Jumlah Guru',
            data: totalTeachersData,
            backgroundColor: '#ffc107',
            borderColor: '#e0a800',
            borderWidth: 1
          }]
        },
        options: {
          maintainAspectRatio: false,
          responsive: true,
          legend: { display: false },
          scales: {
            xAxes: [{ ticks: { beginAtZero: true, stepSize: 2 } }]
          }
        }
      });
    });
  </script>
@endpush
