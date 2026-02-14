@extends('backend.layouts.app')

@section('title', 'Laporan & Statistik')
@section('page_title', 'Laporan & Statistik Sekolah')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active">Laporan & Statistik</li>
@endsection

@section('content')
  <div class="row">
    <!-- Student Stats -->
    <div class="col-md-4">
      <div class="card card-primary card-outline">
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
                <b>Lainnya (Pindah/DO)</b> <a
                  class="float-right badge badge-warning">{{ $stats['students']['others'] }}</a>
              </li>
              <li class="list-group-item">
                <b>Total Keseluruhan</b> <a class="float-right badge badge-primary">{{ $stats['students']['total'] }}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Teacher Stats -->
    <div class="col-md-4">
      <div class="card card-warning card-outline">
        <div class="card-header">
          <h3 class="card-title">Statistik Guru & Tendik</h3>
        </div>
        <div class="card-body">
          <canvas id="teacherChart"
            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          <div class="mt-4">
            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>PNS</b> <a class="float-right badge badge-success">{{ $stats['teachers']['pns'] }}</a>
              </li>
              <li class="list-group-item">
                <b>PPPK</b> <a class="float-right badge badge-info">{{ $stats['teachers']['pppk'] }}</a>
              </li>
              <li class="list-group-item">
                <b>Honorer</b> <a class="float-right badge badge-warning">{{ $stats['teachers']['honorer'] }}</a>
              </li>
              <li class="list-group-item">
                <b>Total Keseluruhan</b> <a class="float-right badge badge-primary">{{ $stats['teachers']['total'] }}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- General Stats -->
    <div class="col-md-4">
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

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Cetak Laporan</h3>
        </div>
        <div class="card-body">
          <button class="btn btn-app" onclick="window.print()">
            <i class="fas fa-print"></i> Print
          </button>
          <button class="btn btn-app" onclick="window.print()">
            <i class="fas fa-file-pdf"></i> PDF (Save as)
          </button>
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
      // Student Chart
      var donutChartCanvas = $('#studentChart').get(0).getContext('2d')
      var studentData = {
        labels: [
          'Aktif',
          'Lulusan',
          'Lainnya',
        ],
        datasets: [
          {
            data: [{{ $stats['students']['active'] }}, {{ $stats['students']['graduated'] }}, {{ $stats['students']['others'] }}],
            backgroundColor: ['#00a65a', '#17a2b8', '#ffc107'],
          }
        ]
      }
      var donutOptions = {
        maintainAspectRatio: false,
        responsive: true,
      }
      new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: studentData,
        options: donutOptions
      })

      // Teacher Chart
      var pieChartCanvas = $('#teacherChart').get(0).getContext('2d')
      var teacherData = {
        labels: [
          'PNS',
          'PPPK',
          'Honorer',
        ],
        datasets: [
          {
            data: [{{ $stats['teachers']['pns'] }}, {{ $stats['teachers']['pppk'] }}, {{ $stats['teachers']['honorer'] }}],
            backgroundColor: ['#28a745', '#17a2b8', '#ffc107'],
          }
        ]
      }
      var pieOptions = {
        maintainAspectRatio: false,
        responsive: true,
      }
      new Chart(pieChartCanvas, {
        type: 'pie',
        data: teacherData,
        options: pieOptions
      })
    })
  </script>
@endpush