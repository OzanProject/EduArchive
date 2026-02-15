@extends('backend.layouts.app')

@section('title', 'Dashboard Operator')
@section('page_title', 'Dashboard Operator')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
  <div class="container-fluid">
    <div class="alert alert-success">
      <h5><i class="icon fas fa-check"></i> Selamat Datang, {{ Auth::user()->name }}!</h5>
      Anda login sebagai <strong>Operator Sekolah</strong> di
      <strong>{{ $app_settings['school_name'] ?? tenant('nama_sekolah') }}</strong>.
    </div>

    <div class="row">
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $total_siswa }}</h3>
            <p>Total Siswa Aktif</p>
          </div>
          <div class="icon">
            <i class="fas fa-user-graduate"></i>
          </div>
          <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{ $total_dokumen }}</h3>
            <p>Dokumen Diupload</p>
          </div>
          <div class="icon">
            <i class="fas fa-file-alt"></i>
          </div>
          <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>
  </div>
@endsection