@extends('backend.layouts.app')

@section('title', 'Integrasi Dapodik')
@section('page_title', 'Tarik Data dari Dapodik')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Integrasi Dapodik</li>
@endsection

@section('content')

  {{-- Panduan Integrasi --}}
  <div class="row">
    <div class="col-12">
      <div class="card card-outline card-info">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-book-reader"></i> Panduan Koneksi Web Service Dapodik</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h5><strong>Langkah 1: Atur IP Address di Dapodik</strong></h5>
              <p>Agar EduArchive diizinkan menarik data, daftarkan IP Server EduArchive ke dalam Dapodik sekolah Anda:</p>
              <ol>
                <li>Buka aplikasi <strong>Dapodik</strong> (Gunakan akun Admin/Operator).</li>
                <li>Pilih menu <strong>Pengaturan</strong> &rarr; <strong>Web Service Dapodik</strong>.</li>
                <li>Klik tombol <strong>Tambah</strong>, lalu masukkan data berikut:
                  <ul class="mt-1">
                    <li><strong>Nama Aplikasi:</strong> <code>EduArchive</code></li>
                    <li><strong>IP Address:</strong> 
                      @php
                        $serverIp = request()->server('SERVER_ADDR');
                        $isLocal = in_array($serverIp, ['127.0.0.1', '::1', 'localhost']);
                      @endphp
                      @if($isLocal)
                        <span class="text-danger">Aplikasi EduArchive ini berjalan di Localhost. Masukkan <code>127.0.0.1</code></span>
                      @else
                        <code>{{ $serverIp }}</code> <small class="text-muted">(IP Publik Server ini)</small>
                      @endif
                    </li>
                  </ul>
                </li>
                <li>Klik <strong>Simpan</strong>. Dapodik akan otomatis men-generate sebuah <strong>Key</strong>. Salin Key tersebut.</li>
              </ol>
            </div>
            <div class="col-md-6">
              <h5><strong>Langkah 2: Syarat URL / IP Dapodik</strong></h5>
              <div class="alert alert-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Penting: Aturan Jaringan</h5>
                Jika aplikasi EduArchive ini sudah <strong>dionline-kan (Hosting/VPS)</strong>, EduArchive <strong>TIDAK BISA</strong> menggunakan URL <code>http://localhost:5774</code> atau IP Lokal <code>http://192.168.x.x:5774</code> untuk mengakses Dapodik.
                <br><br>
                <strong>Solusi:</strong> Komputer Dapodik di sekolah Anda WAJIB disambungkan ke internet menggunakan <em>Tunneling</em> (Contoh: <strong>Ngrok</strong> atau <strong>Cloudflare Zero Trust</strong>) agar mendapatkan URL Publik (misal: <code>https://dapodik.sekolahmu.com</code>). Masukkan URL Publik tersebut di form bawah.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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

            <form id="pull-data-form" action="{{ route('adminlembaga.dapodik.pull') }}" method="POST">
              @csrf

              <div class="form-group">
                <label><i class="fas fa-database"></i> Pilih Jenis Data yang Akan Ditarik:</label>
                <select id="data_type" name="data_type" class="form-control" required>
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
              
              <div id="progress-container" class="d-none mb-3">
                <label id="progress-text">Menyiapkan Sinkronisasi...</label>
                <div class="progress">
                  <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                <small id="progress-message" class="form-text text-muted mt-1">Harap tunggu, proses sedang berjalan di latar belakang.</small>
              </div>

              <button type="submit" id="btn-submit-pull" class="btn btn-success btn-lg btn-block">
                <i class="fas fa-cloud-download-alt"></i> Tarik Data dari Dapodik Sekarang
              </button>
            </form>

            <hr>
            
            <form action="{{ route('adminlembaga.dapodik.processQueue') }}" method="POST" onsubmit="return confirm('Sistem akan mengeksekusi antrean yang masih menyangkut di latar belakang. Lanjutkan?')">
              @csrf
              <button type="submit" class="btn btn-warning btn-block">
                <i class="fas fa-cogs"></i> Jalankan Sinkronisasi (Paksa / Manual)
              </button>
              <small class="text-muted d-block text-center mt-1">Gunakan tombol ini jika status sinkronisasi terus menyangkut di "Menunggu Antrean".</small>
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
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pull-data-form');
    if(!form) return; // if not configured

    const btnSubmit = document.getElementById('btn-submit-pull');
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const progressMessage = document.getElementById('progress-message');
    const dataTypeSelect = document.getElementById('data_type');
    let pollingInterval;

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      Swal.fire({
        title: 'Konfirmasi',
        text: 'Proses sinkronisasi akan berjalan di latar belakang. Lanjutkan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Lanjutkan!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          const formData = new FormData(form);
          const url = form.getAttribute('action');
          const dataType = formData.get('data_type');

          // UI Update
          btnSubmit.setAttribute('disabled', 'disabled');
          btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memulai Antrean...';
          progressContainer.classList.remove('d-none');
          updateBar(0, 'Menunggu antrean...', 'bg-success', true);

      fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          // Start Polling
          startPolling(dataType);
        } else {
          showError(data.message || 'Terjadi kesalahan tidak terduga.');
        }
      })
      .catch(err => {
        showError('Terjadi kesalahan server saat mencoba memulai sinkronisasi.');
      });

        } // End of if (result.isConfirmed)
      }); // End of Swal.then
    });

    function startPolling(dataType) {
      pollingInterval = setInterval(() => {
        fetch(`{{ route('adminlembaga.dapodik.progress') }}?data_type=${dataType}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
          .then(res => res.json())
          .then(data => {
            const pct = data.progress || 0;
            const status = data.status || 'idle';
            const msg = data.message || '';

            if (status === 'error') {
              updateBar(pct, msg, 'bg-danger', false);
              clearInterval(pollingInterval);
              enableSubmit();
            } else if (status === 'success') {
              updateBar(100, msg, 'bg-success', false);
              clearInterval(pollingInterval);
              enableSubmit();
            } else {
              // processing or queued
              updateBar(pct, msg, 'bg-success', true);
            }
          })
          .catch(err => console.error('Polling error:', err));
      }, 1000); // Poll every 1 second
    }

    function updateBar(percent, text, colorClass, animated) {
      progressBar.style.width = `${percent}%`;
      progressBar.setAttribute('aria-valuenow', percent);
      progressBar.innerText = `${percent}%`;
      progressMessage.innerText = text;
      
      progressBar.className = `progress-bar ${colorClass}`;
      if (animated) {
        progressBar.classList.add('progress-bar-striped', 'progress-bar-animated');
      }
    }

    function showError(msg) {
      updateBar(100, msg, 'bg-danger', false);
      enableSubmit();
    }

    function enableSubmit() {
      btnSubmit.removeAttribute('disabled');
      btnSubmit.innerHTML = '<i class="fas fa-cloud-download-alt"></i> Tarik Data dari Dapodik Sekarang';
    }
  });
</script>
@endpush
@endsection
