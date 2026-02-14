@extends('backend.layouts.app')

@section('title', 'Dashboard Sekolah')
@section('page_title', 'Dashboard Sekolah')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
  {{-- Broadcast Notifications --}}
  @if(isset($broadcasts) && $broadcasts->count() > 0)
    <div class="row">
      <div class="col-12">
        @foreach($broadcasts as $broadcast)
          <div class="alert alert-{{ $broadcast->type }} alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-info"></i> {{ $broadcast->title }}</h5>
            {{ $broadcast->content }}
            <br>
            <small>Diposting pada: {{ $broadcast->created_at->format('d M Y, H:i') }}</small>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  <div class="container-fluid">
    <div class="alert alert-info">
      <h5><i class="icon fas fa-info"></i> Selamat Datang, {{ Auth::user()->name }}!</h5>
      Anda login sebagai Admin Sekolah di <strong>{{ $app_settings['school_name'] ?? tenant('nama_sekolah') }}</strong>.
      @if(isset($app_settings['school_description']))
        <br>
        <small>{{ $app_settings['school_description'] }}</small>
      @endif
    </div>

    <div class="row">
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $data['total_guru'] }}</h3>
            <p>Total Guru</p>
          </div>
          <div class="icon">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{ $data['total_siswa'] }}</h3>
            <p>Total Siswa</p>
          </div>
          <div class="icon">
            <i class="fas fa-user-graduate"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ $data['total_kelas'] }}</h3>
            <p>Total Kelas</p>
          </div>
          <div class="icon">
            <i class="fas fa-chalkboard"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- Storage Usage Widget -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
          <div class="inner">
            @php
              $used = $data['storage_usage'];
              $limit = $data['storage_limit'];
              $percentage = ($used / $limit) * 100;

              // Format Bytes
              $units = ['B', 'KB', 'MB', 'GB', 'TB'];
              $bytes = max($used, 0);
              $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
              $pow = min($pow, count($units) - 1);
              $usedFormatted = round($bytes / (1024 ** $pow), 2) . ' ' . $units[$pow];

              $bytesLimit = max($limit, 0);
              $powLimit = floor(($bytesLimit ? log($bytesLimit) : 0) / log(1024));
              $powLimit = min($powLimit, count($units) - 1);
              $limitFormatted = round($bytesLimit / (1024 ** $powLimit), 2) . ' ' . $units[$powLimit];
            @endphp
            <h3>{{ round($percentage, 1) }}<sup style="font-size: 20px">%</sup></h3>
            <p>Penyimpanan: {{ $usedFormatted }} / {{ $limitFormatted }}</p>
          </div>
          <div class="icon">
            <i class="fas fa-hdd"></i>
          </div>
          <div class="small-box-footer">
            <div class="progress" style="height: 5px; margin: 0 10px;">
              <div class="progress-bar" style="width: {{ $percentage }}%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection