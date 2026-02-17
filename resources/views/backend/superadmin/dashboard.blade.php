@extends('backend.layouts.app')

@section('title', 'Super Admin Dashboard')
@section('page_title', 'Dashboard')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
  <div class="container-fluid">

    @if($data['sekolah_pending'] > 0)
      <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
        Terdapat <b>{{ $data['sekolah_pending'] }}</b> sekolah baru yang menunggu persetujuan (status pending).
        <a href="{{ route('superadmin.tenants.index') }}" class="text-dark text-bold ml-2">Lihat Data <i
            class="fas fa-arrow-right"></i></a>
      </div>
    @endif

    <div class="row">
      <!-- Small box: Total Schools -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $data['total_sekolah'] }}</h3>
            <p>Total Sekolah</p>
          </div>
          <div class="icon">
            <i class="fas fa-school"></i>
          </div>
          <a href="{{ route('superadmin.tenants.index') }}" class="small-box-footer">Lihat Detail <i
              class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- Small box: Total Students -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{ number_format($data['total_siswa']) }}</h3>
            <p>Total Siswa</p>
          </div>
          <div class="icon">
            <i class="fas fa-user-graduate"></i>
          </div>
          <a href="{{ route('superadmin.monitoring.index') }}" class="small-box-footer">Lihat Detail <i
              class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- Small box: Total Teachers -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ number_format($data['total_guru']) }}</h3>
            <p>Total Guru & Tendik</p>
          </div>
          <div class="icon">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
          <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- Small box: Storage Usage -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{ $data['storage_usage'] }}</h3>
            <p>Penggunaan Storage</p>
          </div>
          <div class="icon">
            <i class="fas fa-hdd"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
      <div class="col-md-6">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">Distribusi Jenjang Sekolah</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <canvas id="levelChart"
              style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-success card-outline">
          <div class="card-header">
            <h3 class="card-title">Status Sekolah</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <canvas id="statusChart"
              style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Recent Registrations -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Pendaftaran Sekolah Terbaru</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table m-0">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nama Sekolah</th>
                    <th>Jenjang</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($recent_schools as $school)
                    <tr>
                      <td><a href="{{ route('superadmin.tenants.show', $school->id) }}">{{ $school->id }}</a></td>
                      <td>{{ $school->nama_sekolah ?? '-' }}</td>
                      <td>{{ $school->jenjang ?? '-' }}</td>
                      <td>
                        @if($school->status_aktif)
                          <span class="badge badge-success">Aktif</span>
                        @else
                          <span class="badge badge-warning">Pending</span>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center">Belum ada data sekolah.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer clearfix">
            <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-sm btn-info float-left">Kelola Sekolah</a>
          </div>
        </div>
      </div>

      <!-- Recent Audit Logs -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Aktivitas Akses Dokumen Terbaru</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table m-0">
                <thead>
                  <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Aktivitas</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($recent_logs as $log)
                    @php
                      $details = json_decode($log->details, true);
                      $docName = $details['document_name'] ?? 'Dokumen';
                      $user = $log->user->name ?? 'System';
                    @endphp
                    <tr>
                      <td><small>{{ $log->created_at->diffForHumans() }}</small></td>
                      <td>{{ $user }}</td>
                      <td>
                        <small>Mengakses {{ $docName }}</small>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="3" class="text-center">Belum ada aktivitas.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer clearfix">
            <a href="{{ route('superadmin.monitoring.audit_logs') }}" class="btn btn-sm btn-secondary float-right">Lihat
              Semua Log</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('adminlte3/plugins/chart.js/Chart.min.js') }}"></script>
  <script>
    $(function () {
      // School Level Chart
      var levelCanvas = $('#levelChart').get(0).getContext('2d')
      var levelData = {
        labels: {!! json_encode($data['school_levels']->keys()) !!},
        datasets: [{
          data: {!! json_encode($data['school_levels']->values()) !!},
          backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
        }]
      }
      var pieOptions = {
        maintainAspectRatio: false,
        responsive: true,
      }
      new Chart(levelCanvas, {
        type: 'pie',
        data: levelData,
        options: pieOptions
      })

      // School Status Chart
      var statusCanvas = $('#statusChart').get(0).getContext('2d')
      var statusData = {
        labels: ['Aktif', 'Pending / Non-Aktif'],
        datasets: [{
          data: [{{ $data['sekolah_aktif'] }}, {{ $data['sekolah_pending'] }}],
          backgroundColor: ['#00a65a', '#f39c12'],
        }]
      }
      new Chart(statusCanvas, {
        type: 'doughnut',
        data: statusData,
        options: pieOptions
      })
    })
  </script>
@endpush