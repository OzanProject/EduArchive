@extends('backend.layouts.app')

@section('title', 'Laporan Sekolah: ' . ($tenant->nama_sekolah ?? $tenant->id))
@section('page_title', 'Laporan Statistik: ' . ($tenant->nama_sekolah ?? $tenant->id))

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.reports.index') }}">Laporan Sekolah</a></li>
  <li class="breadcrumb-item active">{{ $tenant->id }}</li>
@endsection

@section('content')
  <div class="row">
    <!-- Student Stats (General) -->
    <div class="col-md-4">
      <div class="card card-primary card-outline h-100">
        <div class="card-header">
          <h3 class="card-title">Statistik Siswa</h3>
        </div>
        <div class="card-body">
          <canvas id="studentChart"
            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          <div class="mt-4">
            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Siswa Aktif</b> <a class="float-right badge badge-success">{{ $stats['students']['active'] }}</a>
              </li>
              <li class="list-group-item">
                <b>Lulusan</b> <a class="float-right badge badge-info">{{ $stats['students']['graduated'] }}</a>
              </li>
              <li class="list-group-item">
                <b>Lainnya</b> <a class="float-right badge badge-warning">{{ $stats['students']['others'] }}</a>
              </li>
              <li class="list-group-item">
                <b>Total</b> <a class="float-right badge badge-primary">{{ $stats['students']['total'] }}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Gender Stats -->
    <div class="col-md-4">
      <div class="card card-info card-outline h-100">
        <div class="card-header">
          <h3 class="card-title">Jenis Kelamin</h3>
        </div>
        <div class="card-body">
          <canvas id="genderChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          <div class="mt-4">
            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Laki-laki</b> <a class="float-right badge badge-primary">{{ $stats['gender']['L'] }}</a>
              </li>
              <li class="list-group-item">
                <b>Perempuan</b> <a class="float-right badge badge-danger">{{ $stats['gender']['P'] }}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Age Stats -->
    <div class="col-md-4">
      <div class="card card-success card-outline h-100">
        <div class="card-header">
          <h3 class="card-title">Demografi Usia</h3>
        </div>
        <div class="card-body">
          <canvas id="ageChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          <div class="mt-4">
            <ul class="list-group list-group-unbordered mb-3">
              @foreach ($stats['age_stats'] as $label => $val)
                <li class="list-group-item">
                  <b>{{ $label }} Thn</b> <a class="float-right badge badge-success">{{ $val }}</a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <!-- Class Stats -->
    <div class="col-md-8">
      <div class="card card-secondary card-outline">
        <div class="card-header">
          <h3 class="card-title">Distribusi Per Kelas (Aktif)</h3>
        </div>
        <div class="card-body">
          <canvas id="classChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
        </div>
      </div>
    </div>

    <!-- Teacher & General Stats -->
    <div class="col-md-4">
      <div class="card card-warning card-outline">
        <div class="card-header">
          <h3 class="card-title">Statistik Guru & Arsip</h3>
        </div>
        <div class="card-body">
          <div class="info-box mb-3 bg-warning">
            <span class="info-box-icon"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Guru</span>
              <span class="info-box-number">{{ $stats['teachers']['total'] }} Orang</span>
            </div>
          </div>
          <div class="info-box mb-3 bg-info">
            <span class="info-box-icon"><i class="fas fa-school"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Kelas</span>
              <span class="info-box-number">{{ $stats['classrooms'] }} Rombel</span>
            </div>
          </div>
          <div class="info-box mb-3 bg-success">
            <span class="info-box-icon"><i class="fas fa-file-archive"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Arsip Dokumen</span>
              <span class="info-box-number">{{ $stats['school_documents'] }} File</span>
            </div>
          </div>
          <div class="info-box mb-3 bg-danger">
            <span class="info-box-icon"><i class="fas fa-file-pdf"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Dokumen Siswa</span>
              <span class="info-box-number">{{ $stats['documents'] }} File</span>
            </div>
          </div>
          <hr>
          <hr>
          <div class="text-center">
            <a href="{{ route('superadmin.reports.pdf', $tenant->id) }}" class="btn btn-danger btn-block mb-2">
              <i class="fas fa-file-pdf"></i> Cetak Laporan (PDF)
            </a>
            <button class="btn btn-primary btn-block mb-2" onclick="window.print()">
              <i class="fas fa-print"></i> Cetak Halaman (Browser)
            </button>
            <a href="{{ route('superadmin.reports.index') }}" class="btn btn-default btn-block">
              <i class="fas fa-arrow-left"></i> Kembali
            </a>
          </div>
        </div>
      </div>
    </div>
  <div class="row mt-4">
    <!-- Learning Activities Stats -->
    <div class="col-md-6">
      <div class="card card-info card-outline h-100">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-calendar-check mr-1"></i> Monitoring Kegiatan Pembelajaran</h3>
        </div>
        <div class="card-body">
          <div class="row text-center mb-3">
            <div class="col-6">
              <h3 class="font-weight-bold">{{ $stats['learning_activities']['total'] }}</h3>
              <p class="text-muted small">Total Kegiatan</p>
            </div>
            <div class="col-6">
              <h3 class="font-weight-bold text-success">{{ $stats['learning_activities']['approved'] }}</h3>
              <p class="text-muted small">Disetujui</p>
            </div>
          </div>
          <div class="row text-center">
            <div class="col-6">
              <h3 class="font-weight-bold text-warning">{{ $stats['learning_activities']['pending'] }}</h3>
              <p class="text-muted small">Pending</p>
            </div>
            <div class="col-6">
              <h3 class="font-weight-bold text-danger">{{ $stats['learning_activities']['rejected'] }}</h3>
              <p class="text-muted small">Ditolak</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Infrastructure Stats -->
    <div class="col-md-6">
      <div class="card card-purple card-outline h-100">
        <div class="card-header">
          <h3 class="card-title text-purple"><i class="fas fa-tools mr-1"></i> Usulan Sarpras (Infrastruktur)</h3>
        </div>
        <div class="card-body">
          <div class="row text-center mb-3">
            <div class="col-6">
              <h3 class="font-weight-bold">{{ $stats['infrastructure']['total'] }}</h3>
              <p class="text-muted small">Total Usulan</p>
            </div>
            <div class="col-6">
              <h3 class="font-weight-bold text-primary">{{ $stats['infrastructure']['rkb'] }}</h3>
              <p class="text-muted small">RKB</p>
            </div>
          </div>
          <div class="row text-center">
            <div class="col-6">
              <h3 class="font-weight-bold text-info">{{ $stats['infrastructure']['rehab'] }}</h3>
              <p class="text-muted small">REHAB</p>
            </div>
            <div class="col-6">
              <h3 class="font-weight-bold text-secondary">{{ $stats['infrastructure']['other'] }}</h3>
              <p class="text-muted small">Lain-lain</p>
            </div>
          </div>
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

      /* Hide sidebar, footer, navbar, buttons explicitly */
      .main-sidebar,
      .main-header,
      .main-footer,
      .no-print,
      .card-header .card-tools,
      .btn,
      .breadcrumb {
        display: none !important;
      }

      /* Ensure cards look good */
      .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
        break-inside: avoid;
      }

      /* Ensure charts are responsive */
      canvas {
        max-width: 100% !important;
      }

      /* Layout adjustments */
      .row {
        display: flex;
        flex-wrap: wrap;
      }

      .col-md-4 {
        width: 33.333333%;
        flex: 0 0 33.333333%;
      }
    }
  </style>
@endpush

@push('scripts')
  <script src="{{ asset('adminlte3/plugins/chart.js/Chart.min.js') }}"></script>
  <script>
    $(function () {
      // 1. Student Status Chart
      new Chart($('#studentChart').get(0).getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: ['Aktif', 'Lulusan', 'Lainnya'],
          datasets: [{
            data: [{{ $stats['students']['active'] }}, {{ $stats['students']['graduated'] }}, {{ $stats['students']['others'] }}],
            backgroundColor: ['#28a745', '#17a2b8', '#ffc107'],
          }]
        },
        options: { maintainAspectRatio: false, responsive: true }
      });

      // 2. Gender Chart
      new Chart($('#genderChart').get(0).getContext('2d'), {
        type: 'pie',
        data: {
          labels: ['Laki-laki', 'Perempuan'],
          datasets: [{
            data: [{{ $stats['gender']['L'] }}, {{ $stats['gender']['P'] }}],
            backgroundColor: ['#007bff', '#dc3545'],
          }]
        },
        options: { maintainAspectRatio: false, responsive: true }
      });

      // 3. Age Chart
      new Chart($('#ageChart').get(0).getContext('2d'), {
        type: 'bar',
        data: {
          labels: {!! json_encode(array_keys($stats['age_stats'])) !!},
          datasets: [{
            label: 'Jumlah Siswa',
            data: {!! json_encode(array_values($stats['age_stats'])) !!},
            backgroundColor: '#28a745',
          }]
        },
        options: {
          maintainAspectRatio: false,
          responsive: true,
          scales: {
            yAxes: [{
              ticks: { beginAtZero: true, stepSize: 1 }
            }]
          }
        }
      });

      // 4. Class Distribution Chart
      new Chart($('#classChart').get(0).getContext('2d'), {
        type: 'bar',
        data: {
          labels: {!! json_encode(array_keys($stats['classroom_stats'])) !!},
          datasets: [{
            label: 'Siswa Aktif',
            data: {!! json_encode(array_values($stats['classroom_stats'])) !!},
            backgroundColor: '#6c757d',
          }]
        },
        options: {
          maintainAspectRatio: false,
          responsive: true,
          scales: {
            yAxes: [{
              ticks: { beginAtZero: true, stepSize: 1 }
            }]
          }
        }
      });
    })
  </script>
@endpush