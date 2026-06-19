@extends('backend.layouts.app')

@section('title', 'Integrasi Dapodik')
@section('page_title', 'Tarik Data dari Dapodik')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Integrasi Dapodik</li>
@endsection

@section('content')
  <div class="row">
    <!-- Kolom Kiri: Pengaturan Koneksi -->
    <div class="col-md-5">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">1. Pengaturan Web Service Dapodik</h3>
        </div>
        <form action="{{ route('adminlembaga.dapodik.save') }}" method="POST">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="dapodik_url">IP Address / URL Dapodik</label>
              <input type="url" class="form-control" id="dapodik_url" name="dapodik_url" value="{{ $dapodikUrl }}" placeholder="Misal: http://192.168.1.100:5774" required>
              <small class="form-text text-muted">Pastikan server EduArchive bisa menjangkau IP ini. Jika berbeda jaringan, gunakan IP Publik Dapodik / Ngrok.</small>
            </div>
            <div class="form-group">
              <label for="dapodik_key">Key Web Service</label>
              <input type="text" class="form-control" id="dapodik_key" name="dapodik_key" value="{{ $dapodikKey }}" placeholder="Masukkan Key Web Service Dapodik..." required>
            </div>
          </div>
          <div class="card-footer d-flex justify-content-between">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Pengaturan</button>
            
            <!-- Tombol Test Koneksi (form terpisah) -->
            <button type="button" class="btn btn-info" onclick="document.getElementById('form-test-koneksi').submit()">
              <i class="fas fa-plug"></i> Test Koneksi
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Hidden form for Test Koneksi -->
    <form id="form-test-koneksi" action="{{ route('adminlembaga.dapodik.test') }}" method="POST" class="d-none">
      @csrf
    </form>

    <!-- Kolom Kanan: Tarik Data -->
    <div class="col-md-7">
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">2. Tarik Data Sinkronisasi</h3>
        </div>
        <div class="card-body">
          <p>Fitur ini akan memanggil API Dapodik secara langsung dan memasukkan datanya ke database EduArchive.</p>
          
          <form action="{{ route('adminlembaga.dapodik.pull') }}" method="POST" onsubmit="return confirm('Proses ini mungkin membutuhkan waktu agak lama, jangan tutup halaman saat loading. Lanjutkan?')">
            @csrf
            
            <div class="form-group">
              <label>Pilih Data yang Akan Ditarik:</label>
              <select name="data_type" class="form-control" required>
                <option value="classrooms">Tarik Data Rombongan Belajar (Rombel)</option>
                <option value="teachers">Tarik Data Guru (Tenaga Pendidik)</option>
                <option value="students">Tarik Data Siswa (Peserta Didik)</option>
              </select>
            </div>

            <div class="form-group">
              <label>Metode Sinkronisasi (Jika Data Sudah Ada):</label>
              <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="sync_skip" name="sync_mode" value="skip" checked>
                <label for="sync_skip" class="custom-control-label">Lewati (Hanya Tambah Data Baru)</label>
              </div>
              <div class="custom-control custom-radio mt-2">
                <input class="custom-control-input text-danger" type="radio" id="sync_overwrite" name="sync_mode" value="overwrite">
                <label for="sync_overwrite" class="custom-control-label text-danger">Timpa / Perbarui (Update data lama dengan data Dapodik)</label>
              </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-success btn-lg btn-block">
              <i class="fas fa-cloud-download-alt"></i> Tarik Data Sekarang
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection
