@extends('backend.layouts.app')

@section('title', 'Integrasi Dapodik')
@section('page_title', 'Tarik Data dari Dapodik')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Integrasi Dapodik</li>
@endsection

@section('content')

  {{-- Panduan Langkah 0 --}}
  <div class="callout callout-info">
    <h5><i class="fas fa-info-circle"></i> Cara Mendapatkan Key Web Service Dapodik</h5>
    <p class="mb-1">Sebelum mengisi form di bawah, lakukan langkah berikut di aplikasi <strong>Dapodik</strong> sekolah Anda:</p>
    <ol class="mb-0">
      <li>Buka aplikasi Dapodik, klik menu <strong>Pengaturan</strong> → <strong>Web Service Dapodik</strong>.</li>
      <li>Klik tombol <strong>Tambah</strong>, lalu isi:<br>
        <ul>
          <li><strong>Nama Aplikasi:</strong> EduArchive</li>
          <li>
            <strong>IP Address:</strong>
            @php
              $serverIp = request()->server('SERVER_ADDR');
              $isLocal = in_array($serverIp, ['127.0.0.1', '::1', 'localhost']);
              $publicHost = request()->getHost();
            @endphp
            @if($isLocal)
              {{-- Running on localhost --}}
              <span class="text-muted">(Sedang berjalan di <em>localhost</em>)</span><br>
              <small class="text-danger">
                <i class="fas fa-exclamation-triangle"></i>
                Anda sedang membuka halaman ini dari <strong>jaringan lokal</strong>.
                Jika EduArchive dihosting di internet, masukkan <strong>IP Publik server hosting</strong> Anda ke Dapodik
                (cek di cPanel / VPS panel → "Shared IP Address" atau "Main IP").
              </small>
            @else
              <code>{{ $serverIp }}</code>
              <small class="text-muted d-block">
                (IP publik server EduArchive ini — inilah yang dimasukkan ke kolom IP Address di Dapodik)
              </small>
            @endif
          </li>
        </ul>
      </li>
      <li>Klik <strong>Simpan</strong>. Dapodik akan men-generate sebuah <strong>Key</strong> (seperti <code>w4lzw4bWiWZRPf</code>).</li>
      <li>Salin Key tersebut, lalu isi form di bawah ini.</li>
    </ol>
  </div>

  <div class="row">
    {{-- Kolom Kiri: Pengaturan Koneksi --}}
    <div class="col-md-5">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">1. Pengaturan Koneksi Dapodik</h3>
        </div>
        <form action="{{ route('adminlembaga.dapodik.save') }}" method="POST">
          @csrf
          <div class="card-body">

            @if(session('success'))
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
              </div>
            @endif
            @if(session('error'))
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
              </div>
            @endif
            @if(session('warning'))
              <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('warning') }}
              </div>
            @endif

            <div class="form-group">
              <label for="dapodik_url">IP Address / URL Dapodik <span class="text-danger">*</span></label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-server"></i></span>
                </div>
                <input type="text" class="form-control" id="dapodik_url" name="dapodik_url"
                  value="{{ $dapodikUrl }}"
                  placeholder="http://localhost:5774 atau http://192.168.1.100:5774">
              </div>
              <small class="form-text text-muted">
                <i class="fas fa-info-circle"></i>
                URL ini adalah alamat <strong>server Dapodik</strong> sekolah (biasanya port <code>5774</code>).
                Jika EduArchive dan Dapodik tidak satu jaringan, gunakan IP Publik / Ngrok.
              </small>
            </div>

            <div class="form-group">
              <label for="dapodik_key">Key Web Service (dari Dapodik) <span class="text-danger">*</span></label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                <input type="text" class="form-control" id="dapodik_key" name="dapodik_key"
                  value="{{ $dapodikKey }}"
                  placeholder="Contoh: w4lzw4bWiWZRPf">
              </div>
              <small class="form-text text-muted">
                Key ini didapatkan dari menu <strong>Pengaturan → Web Service Dapodik</strong> di aplikasi Dapodik Anda (kolom "Key").
              </small>
            </div>

            {{-- Tabel status koneksi --}}
            <table class="table table-bordered table-sm mt-3">
              <tr>
                <th class="w-50">Status Pengaturan</th>
                <td>
                  @if($dapodikUrl && $dapodikKey)
                    <span class="badge badge-success"><i class="fas fa-check"></i> Sudah Dikonfigurasi</span>
                  @else
                    <span class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i> Belum Dikonfigurasi</span>
                  @endif
                </td>
              </tr>
              @if($dapodikUrl)
              <tr>
                <th>URL Dapodik</th>
                <td><code>{{ $dapodikUrl }}</code></td>
              </tr>
              @endif
            </table>

          </div>
          <div class="card-footer d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Simpan Pengaturan
            </button>
            <button type="button" class="btn btn-info" onclick="document.getElementById('form-test-koneksi').submit()">
              <i class="fas fa-plug"></i> Test Koneksi
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Hidden form for Test Koneksi --}}
    <form id="form-test-koneksi" action="{{ route('adminlembaga.dapodik.test') }}" method="POST" class="d-none">
      @csrf
    </form>

    {{-- Kolom Kanan: Tarik Data --}}
    <div class="col-md-7">
      <div class="card {{ ($dapodikUrl && $dapodikKey) ? 'card-success' : 'card-secondary' }}">
        <div class="card-header">
          <h3 class="card-title">2. Tarik Data Sinkronisasi</h3>
        </div>
        <div class="card-body">
          @if(!$dapodikUrl || !$dapodikKey)
            <div class="alert alert-warning">
              <i class="fas fa-lock"></i> Harap simpan <strong>Pengaturan Koneksi Dapodik</strong> terlebih dahulu sebelum bisa menarik data.
            </div>
          @else
            <p class="text-muted">Fitur ini akan menghubungi server Dapodik secara langsung dan memasukkan datanya ke database EduArchive.</p>

            <form action="{{ route('adminlembaga.dapodik.pull') }}" method="POST"
              onsubmit="return confirm('Proses ini mungkin membutuhkan waktu. Jangan tutup halaman saat loading. Lanjutkan?')">
              @csrf

              <div class="form-group">
                <label><i class="fas fa-database"></i> Pilih Jenis Data yang Akan Ditarik:</label>
                <select name="data_type" class="form-control" required>
                  <option value="classrooms">📚 Rombongan Belajar (Rombel / Kelas)</option>
                  <option value="teachers">👩‍🏫 Guru & Tenaga Pendidik</option>
                  <option value="students">👨‍🎓 Peserta Didik (Siswa)</option>
                </select>
                <small class="form-text text-muted">
                  <strong>Disarankan:</strong> Tarik Rombel terlebih dahulu, lalu Guru, kemudian Siswa.
                </small>
              </div>

              <div class="form-group">
                <label><i class="fas fa-sync-alt"></i> Metode Sinkronisasi (Jika Data Sudah Ada):</label>
                <div class="custom-control custom-radio">
                  <input class="custom-control-input" type="radio" id="sync_skip" name="sync_mode" value="skip" checked>
                  <label for="sync_skip" class="custom-control-label">
                    <strong>Lewati</strong> — Hanya tambah data baru, data lama tidak diubah
                  </label>
                </div>
                <div class="custom-control custom-radio mt-2">
                  <input class="custom-control-input" type="radio" id="sync_overwrite" name="sync_mode" value="overwrite">
                  <label for="sync_overwrite" class="custom-control-label text-danger">
                    <strong>Timpa / Perbarui</strong> — Update data lama sesuai data Dapodik terbaru
                  </label>
                </div>
              </div>

              <hr>
              <button type="submit" class="btn btn-success btn-lg btn-block">
                <i class="fas fa-cloud-download-alt"></i> Tarik Data dari Dapodik Sekarang
              </button>
            </form>
          @endif
        </div>
      </div>

      {{-- Info Endpoint Dapodik --}}
      <div class="card card-outline card-info collapsed-card">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-code"></i> Endpoint API Dapodik yang Digunakan</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <table class="table table-sm table-bordered">
            <thead>
              <tr class="bg-light">
                <th>Data</th>
                <th>Endpoint</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Rombel</td>
                <td><code>/WebService/getRombonganBelajar?npsn={{ tenant('npsn') }}</code></td>
              </tr>
              <tr>
                <td>Guru</td>
                <td><code>/WebService/getGuru?npsn={{ tenant('npsn') }}</code></td>
              </tr>
              <tr>
                <td>Siswa</td>
                <td><code>/WebService/getPesertaDidik?npsn={{ tenant('npsn') }}</code></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
