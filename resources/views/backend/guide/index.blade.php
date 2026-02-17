@extends('backend.layouts.app')

@section('title', 'Panduan Website')
@section('page_title', 'Panduan Penggunaan Sistem')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route($prefix . 'dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Panduan</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-book-reader mr-2"></i> Selamat Datang di Panduan EduArchive</h3>
        </div>
        <div class="card-body">
          <div class="callout callout-info">
            <h5><i class="fas fa-info-circle"></i> Tentang Sistem</h5>
            <p>EduArchive adalah sistem manajemen arsip dokumen siswa berbasis web yang memudahkan sekolah dalam
              mengelola, menyimpan, dan mengakses dokumen-dokumen penting siswa secara digital.</p>
          </div>

          {{-- Navigation Tabs --}}
          <ul class="nav nav-tabs" id="guideTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="intro-tab" data-toggle="pill" href="#intro" role="tab">
                <i class="fas fa-home"></i> Pengenalan
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="students-tab" data-toggle="pill" href="#students" role="tab">
                <i class="fas fa-user-graduate"></i> Kelola Siswa
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="documents-tab" data-toggle="pill" href="#documents" role="tab">
                <i class="fas fa-file-alt"></i> Kelola Dokumen
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="teachers-tab" data-toggle="pill" href="#teachers" role="tab">
                <i class="fas fa-chalkboard-teacher"></i> Kelola GTK
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="settings-tab" data-toggle="pill" href="#settings" role="tab">
                <i class="fas fa-cog"></i> Pengaturan
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="faq-tab" data-toggle="pill" href="#faq" role="tab">
                <i class="fas fa-question-circle"></i> FAQ
              </a>
            </li>
          </ul>

          <div class="tab-content mt-3" id="guideTabContent">

            {{-- INTRO TAB --}}
            <div class="tab-pane fade show active" id="intro" role="tabpanel">
              <h4 class="text-primary"><i class="fas fa-rocket"></i> Memulai Penggunaan</h4>
              <hr>

              <div class="row">
                <div class="col-md-6">
                  <div class="info-box bg-light">
                    <span class="info-box-icon bg-info"><i class="fas fa-user-shield"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text"><strong>Admin Sekolah</strong></span>
                      <span class="info-box-number">Akses Penuh ke Semua Fitur</span>
                      <small>Mengelola seluruh data sekolah, siswa, guru, dokumen, dan pengaturan sistem.</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="info-box bg-light">
                    <span class="info-box-icon bg-success"><i class="fas fa-user-tie"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text"><strong>Operator</strong></span>
                      <span class="info-box-number">Akses Terbatas</span>
                      <small>Mengelola data siswa dan dokumen siswa sesuai wewenang yang diberikan.</small>
                    </div>
                  </div>
                </div>
              </div>

              <h5 class="mt-4"><i class="fas fa-list-ol"></i> Langkah Awal</h5>
              <ol class="lead">
                <li>Login menggunakan email dan password yang telah diberikan oleh Super Admin</li>
                <li>Lengkapi <strong>Profil Sekolah</strong> di menu <code>Pengaturan</code></li>
                <li>Upload <strong>Logo Sekolah</strong> dan <strong>Dokumen Kop Surat</strong></li>
                <li>Mulai menambahkan data <strong>Siswa</strong> dan <strong>Guru</strong></li>
                <li>Upload <strong>Dokumen Siswa</strong> untuk pengarsipan digital</li>
              </ol>

              <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> <strong>Penting!</strong>
                Pastikan data yang diinput sudah benar karena akan digunakan untuk laporan resmi dan cetak dokumen.
              </div>
            </div>

            {{-- STUDENTS TAB --}}
            <div class="tab-pane fade" id="students" role="tabpanel">
              <h4 class="text-primary"><i class="fas fa-user-graduate"></i> Panduan Kelola Data Siswa</h4>
              <hr>

              <h5><i class="fas fa-plus-circle text-success"></i> Menambah Data Siswa Baru</h5>
              <ol>
                <li>Klik menu <strong>Data Akademik > Data Siswa Aktif</strong></li>
                <li>Klik tombol <span class="badge badge-primary"><i class="fas fa-plus"></i> Tambah Siswa</span></li>
                <li>Isi form dengan data lengkap:
                  <ul>
                    <li><strong>NIK</strong>: Nomor Induk Kependudukan (16 digit)</li>
                    <li><strong>NISN</strong>: Nomor Induk Siswa Nasional</li>
                    <li><strong>Nama Lengkap</strong>: Sesuai Akta/KK</li>
                    <li><strong>Jenis Kelamin</strong>: Laki-laki / Perempuan</li>
                    <li><strong>Kelas</strong>: Pilih kelas aktif siswa</li>
                    <li><strong>Tahun Masuk</strong>: Tahun siswa diterima</li>
                    <li><strong>Foto Profil</strong>: Pas foto siswa (opsional)</li>
                  </ul>
                </li>
                <li>Klik tombol <span class="badge badge-success">Simpan</span></li>
              </ol>

              <h5 class="mt-4"><i class="fas fa-edit text-warning"></i> Mengubah Data Siswa</h5>
              <ol>
                <li>Cari siswa yang ingin diubah di tabel</li>
                <li>Klik tombol <span class="badge badge-warning"><i class="fas fa-edit"></i> Edit</span></li>
                <li>Ubah data yang diperlukan</li>
                <li>Klik <span class="badge badge-success">Update</span></li>
              </ol>

              <h5 class="mt-4"><i class="fas fa-graduation-cap text-info"></i> Meluluskan Siswa</h5>
              <ol>
                <li>Pilih siswa yang akan diluluskan (centang checkbox)</li>
                <li>Pilih <strong>Aksi Massal > Luluskan Siswa</strong></li>
                <li>Masukkan <strong>Tahun Lulus</strong> dan <strong>No. Seri Ijazah</strong></li>
                <li>Klik <span class="badge badge-success">Luluskan</span></li>
                <li>Data siswa akan dipindah ke <strong>Data Siswa Lulusan</strong></li>
              </ol>

              <div class="callout callout-success">
                <h5><i class="fas fa-lightbulb"></i> Tips!</h5>
                <p>Gunakan fitur <strong>Import Excel</strong> untuk menambahkan banyak siswa sekaligus. Template bisa
                  diunduh dari halaman Data Siswa.</p>
              </div>
            </div>

            {{-- DOCUMENTS TAB --}}
            <div class="tab-pane fade" id="documents" role="tabpanel">
              <h4 class="text-primary"><i class="fas fa-file-alt"></i> Panduan Kelola Dokumen Siswa</h4>
              <hr>

              <h5><i class="fas fa-upload text-success"></i> Upload Dokumen Siswa</h5>
              <ol>
                <li>Klik menu <strong>Arsip Dokumen > Dokumen Siswa</strong></li>
                <li>Klik tombol <span class="badge badge-primary"><i class="fas fa-plus"></i> Upload Dokumen</span></li>
                <li>Pilih <strong>Nama Siswa</strong> dari dropdown</li>
                <li>Pilih <strong>Jenis Dokumen</strong> (Ijazah, KK, Akta, dll)</li>
                <li>Upload file (PDF max 5MB, atau gambar max 2MB)</li>
                <li>Tambahkan <strong>Keterangan</strong> jika diperlukan</li>
                <li>Klik <span class="badge badge-success">Simpan</span></li>
              </ol>

              <h5 class="mt-4"><i class="fas fa-search text-info"></i> Mencari Dokumen</h5>
              <ul>
                <li>Gunakan <strong>Filter Status</strong>: Siswa Aktif / Lulusan</li>
                <li>Gunakan <strong>Search Box</strong> untuk cari berdasarkan nama siswa atau NISN</li>
                <li>Klik tombol <span class="badge badge-info"><i class="fas fa-eye"></i> Lihat</span> untuk preview
                  dokumen</li>
              </ul>

              <h5 class="mt-4"><i class="fas fa-file-pdf text-danger"></i> Jenis Dokumen yang Bisa Diupload</h5>
              <div class="row">
                <div class="col-md-6">
                  <ul>
                    <li><i class="fas fa-check text-success"></i> Kartu Keluarga (KK)</li>
                    <li><i class="fas fa-check text-success"></i> Akta Kelahiran</li>
                    <li><i class="fas fa-check text-success"></i> Ijazah</li>
                    <li><i class="fas fa-check text-success"></i> SKHUN</li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <ul>
                    <li><i class="fas fa-check text-success"></i> Raport</li>
                    <li><i class="fas fa-check text-success"></i> Surat Keterangan</li>
                    <li><i class="fas fa-check text-success"></i> Foto</li>
                    <li><i class="fas fa-check text-success"></i> Dokumen Lainnya</li>
                  </ul>
                </div>
              </div>

              <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle"></i> <strong>Catatan:</strong>
                Semua dokumen tersimpan aman di server dan hanya bisa diakses oleh admin sekolah dan operator yang
                berwenang.
              </div>
            </div>

            {{-- TEACHERS TAB --}}
            <div class="tab-pane fade" id="teachers" role="tabpanel">
              <h4 class="text-primary"><i class="fas fa-chalkboard-teacher"></i> Panduan Kelola Data Guru & Tendik</h4>
              <hr>

              <h5><i class="fas fa-user-plus text-success"></i> Menambah Data GTK</h5>
              <ol>
                <li>Klik menu <strong>Data Akademik > Data Guru & Tendik</strong></li>
                <li>Klik tombol <span class="badge badge-primary"><i class="fas fa-plus"></i> Tambah GTK</span></li>
                <li>Isi form dengan lengkap:
                  <ul>
                    <li><strong>NIP/NUPTK</strong>: Nomor Induk Pegawai</li>
                    <li><strong>Nama Lengkap</strong></li>
                    <li><strong>Jenis Kelamin</strong></li>
                    <li><strong>Jabatan</strong>: Guru / Tenaga Kependidikan</li>
                    <li><strong>Status</strong>: PNS / Non-PNS</li>
                    <li><strong>Tanggal Lahir</strong></li>
                    <li><strong>No. Telepon</strong></li>
                    <li><strong>Email</strong></li>
                    <li><strong>Alamat</strong></li>
                    <li><strong>Foto</strong> (opsional)</li>
                  </ul>
                </li>
                <li>Klik <span class="badge badge-success">Simpan</span></li>
              </ol>

              <div class="callout callout-warning">
                <h5><i class="fas fa-exclamation-triangle"></i> Perhatian!</h5>
                <p>Data GTK akan ditampilkan di halaman profil publik sekolah. Pastikan data yang diinput sudah akurat.
                </p>
              </div>
            </div>

            {{-- SETTINGS TAB --}}
            <div class="tab-pane fade" id="settings" role="tabpanel">
              <h4 class="text-primary"><i class="fas fa-cog"></i> Panduan Pengaturan Sistem</h4>
              <hr>

              <h5><i class="fas fa-school text-info"></i> Profil Sekolah</h5>
              <p>Menu: <strong>Pengaturan > Profil Sekolah</strong></p>
              <ul>
                <li><strong>Nama Sekolah</strong>: Nama resmi sekolah</li>
                <li><strong>NPSN</strong>: Nomor Pokok Sekolah Nasional</li>
                <li><strong>Alamat Lengkap</strong></li>
                <li><strong>No. Telepon</strong> dan <strong>Email Sekolah</strong></li>
                <li><strong>Nama Kepala Sekolah</strong> dan <strong>NIP</strong></li>
              </ul>

              <h5 class="mt-4"><i class="fas fa-image text-warning"></i> Dokumen & Kop Surat</h5>
              <p>Menu: <strong>Pengaturan > Dokumen & Kop Surat</strong></p>
              <ul>
                <li><strong>Logo Kabupaten</strong>: Logo pemda (kiri)</li>
                <li><strong>Logo Sekolah</strong>: Logo sekolah (kanan)</li>
                <li><strong>Tanda Tangan</strong>: Scan tanda tangan kepala sekolah</li>
                <li><strong>Stempel</strong>: Scan stempel sekolah</li>
              </ul>
              <p class="text-muted"><i class="fas fa-info-circle"></i> Logo dan tanda tangan akan otomatis digunakan saat
                cetak dokumen resmi.</p>

              <h5 class="mt-4"><i class="fas fa-users-cog text-success"></i> Kelola Operator (Admin Sekolah)</h5>
              <p>Menu: <strong>Manajemen User > Operator</strong></p>
              <ol>
                <li>Klik <span class="badge badge-primary"><i class="fas fa-plus"></i> Tambah Operator</span></li>
                <li>Isi Nama, Email, dan Password</li>
                <li>Klik <span class="badge badge-success">Simpan</span></li>
                <li>Operator bisa login dan membantu admin sekolah mengelola data</li>
              </ol>
            </div>

            {{-- FAQ TAB --}}
            <div class="tab-pane fade" id="faq" role="tabpanel">
              <h4 class="text-primary"><i class="fas fa-question-circle"></i> Pertanyaan yang Sering Diajukan (FAQ)</h4>
              <hr>

              <div class="card card-outline card-info collapsed-card">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-question"></i> Bagaimana cara reset password?</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  Hubungi Super Admin melalui support untuk melakukan reset password akun Anda.
                </div>
              </div>

              <div class="card card-outline card-info collapsed-card">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-question"></i> Berapa batas maksimal upload dokumen?</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  Batas penyimpanan ditentukan oleh paket langganan sekolah. Cek di menu <strong>Pengaturan > Info
                    Akun</strong> untuk melihat batas storage Anda.
                </div>
              </div>

              <div class="card card-outline card-info collapsed-card">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-question"></i> Apakah data aman?</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  Ya, semua data ter-enkripsi dan tersimpan di server yang aman. Setiap akses dokumen penting akan
                  tercatat di Audit Log oleh Super Admin.
                </div>
              </div>

              <div class="card card-outline card-info collapsed-card">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-question"></i> Bagaimana cara cetak dokumen siswa?</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  Klik tombol <span class="badge badge-info"><i class="fas fa-print"></i> Cetak</span> di halaman Data
                  Siswa. Sistem akan otomatis menambahkan kop surat dan logo sekolah.
                </div>
              </div>

              <div class="card card-outline card-info collapsed-card">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-question"></i> Apa perbedaan Admin Sekolah dan Operator?</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <strong>Admin Sekolah</strong> memiliki akses penuh ke semua fitur termasuk pengaturan sistem dan
                  menambah operator.
                  <strong>Operator</strong> hanya bisa mengelola data siswa dan dokumen, tidak bisa mengubah pengaturan
                  sistem.
                </div>
              </div>

              <div class="alert alert-success mt-4">
                <h5><i class="fas fa-headset"></i> Butuh Bantuan Lebih Lanjut?</h5>
                <p>Hubungi tim support kami melalui tombol <strong>Hubungi Support</strong> yang tersedia di menu
                  <strong>Pengaturan > Info Akun</strong>.</p>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection