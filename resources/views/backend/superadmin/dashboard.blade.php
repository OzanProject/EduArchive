@extends('backend.layouts.app')

@section('title', 'Super Admin Dashboard')
@section('page_title', 'Dashboard')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
  <div class="container-fluid">
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
          <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- Small box: Total Documents -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ number_format($data['total_dokumen']) }}</h3>
            <p>Total Dokumen</p>
          </div>
          <div class="icon">
            <i class="fas fa-file-alt"></i>
          </div>
          <a href="{{ route('superadmin.monitoring.index') }}" class="small-box-footer">Lihat Detail <i
              class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- Small box: Recent Activity -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{ $data['storage_usage'] }}</h3>
            <p>Storage Usage</p>
          </div>
          <div class="icon">
            <i class="fas fa-hdd"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header border-transparent">
            <h3 class="card-title">Aktivitas Akses Dokumen Terbaru</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table m-0">
                <thead>
                  <tr>
                    <th>Waktu</th>
                    <th>Admin</th>
                    <th>Sekolah</th>
                    <th>Siswa</th>
                    <th>Dokumen</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($recent_logs as $log)
                    @php
                      $details = json_decode($log->details, true);
                    @endphp
                    <tr>
                      <td>{{ $log->created_at->diffForHumans() }}</td>
                      <td>{{ $log->user->name }}</td>
                      <td><span class="badge badge-info">{{ $log->tenant_id }}</span></td>
                      <td>{{ $details['student_nisn'] ?? '-' }}</td>
                      <td>{{ $details['document_name'] ?? '-' }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center">Belum ada aktivitas.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            <!-- /.table-responsive -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <a href="{{ route('superadmin.monitoring.audit_logs') }}" class="btn btn-sm btn-secondary float-right">Lihat
              Semua Log</a>
          </div>
          <!-- /.card-footer -->
        </div>
      </div>
    </div>
  </div>
@endsection