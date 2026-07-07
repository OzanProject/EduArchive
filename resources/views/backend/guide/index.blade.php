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
            <h5><i class="fas fa-info-circle"></i> Tentang Sistem EduArchive</h5>
            <p>EduArchive adalah sistem informasi dan manajemen arsip terpadu untuk Satuan Pendidikan. Melalui sistem ini, Anda dapat mengelola integrasi Dapodik, data kesiswaan, kelulusan, dokumen siswa, arsip kelembagaan, hingga laporan statistik secara real-time.</p>
          </div>

          {{-- Navigation Tabs --}}
          <ul class="nav nav-tabs" id="guideTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="intro-tab" data-toggle="pill" href="#intro" role="tab">
                <i class="fas fa-home"></i> Pengenalan & Integrasi
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="master-tab" data-toggle="pill" href="#master" role="tab">
                <i class="fas fa-database"></i> Data Master
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="kesiswaan-tab" data-toggle="pill" href="#kesiswaan" role="tab">
                <i class="fas fa-user-graduate"></i> Kesiswaan
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="sarana-tab" data-toggle="pill" href="#sarana" role="tab">
                <i class="fas fa-building"></i> Sarana & Admin
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="settings-tab" data-toggle="pill" href="#settings" role="tab">
                <i class="fas fa-cogs"></i> Pengaturan
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="faq-tab" data-toggle="pill" href="#faq" role="tab">
                <i class="fas fa-question-circle"></i> FAQ
              </a>
            </li>
          </ul>

          <div class="tab-content mt-3" id="guideTabContent">

            {{-- INTRO & DAPODIK TAB --}}
            <div class="tab-pane fade show active" id="intro" role="tabpanel">
              <h4 class="text-primary"><i class="fas fa-rocket"></i> Memulai Penggunaan & Integrasi</h4>
              <hr>

              <h5 class="mt-4"><i class="fas fa-cloud-download-alt text-success"></i> Sinkronisasi Data Dapodik</h5>
              <p>Anda dapat menghemat waktu dengan menarik data (Siswa, GTK, Rombel/Kelas) langsung dari Web Service Dapodik lokal Anda.</p>
              <ol class="lead">
                <li>Buka menu <strong>Integrasi Dapodik</strong> di *sidebar* sebelah kiri.</li>
                <li>Pilih jenis data yang ingin ditarik: <span class="badge badge-info">Siswa</span>, <span class="badge badge-info">GTK</span>, atau <span class="badge badge-info">Rombongan Belajar (Kelas)</span>.</li>
                <li>Klik tombol <strong>Tarik Data Baru</strong>.</li>
                <li>Tunggu proses sinkronisasi selesai. Semakin banyak data, semakin lama waktu yang dibutuhkan.</li>
              </ol>
              
              <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> <strong>Penting!</strong>
                Pastikan aplikasi Dapodik di komputer Anda / server sedang berjalan dan token sinkronisasi (API) sudah diatur dengan benar di menu <strong>Web Service API</strong>.
              </div>

              <h5 class="mt-4"><i class="fas fa-chart-pie text-primary"></i> Laporan & Statistik</h5>
              <p>Setelah data terisi (baik via Dapodik atau manual), Anda dapat memantau ringkasan data sekolah pada menu <strong>Laporan & Statistik</strong>. Menu ini menyajikan visualisasi data berupa grafik mengenai jumlah siswa per kelas, persentase kelulusan, hingga demografi guru.</p>
            </div>

            {{-- DATA MASTER TAB --}}
            <div class="tab-pane fade" id="master" role="tabpanel">
              <h4 class="text-primary"><i class="fas fa-database"></i> Panduan Kelola Data Master</h4>
              <hr>

              <h5><i class="fas fa-chalkboard-teacher text-info"></i> Data Kelas / Rombel</h5>
              <ol>
                <li>Akses menu <strong>Data Master > Data Kelas</strong>.</li>
                <li>Klik tombol <strong>Tambah Kelas</strong> untuk membuat kelas baru.</li>
                <li>Isikan <strong>Tingkat Kelas</strong> dan <strong>Nama Rombel</strong> (misal: VII-A, X IPA 1).</li>
                <li>Anda bisa menugaskan seorang <strong>Wali Kelas</strong> (diambil dari data Guru).</li>
                <li>Data kelas ini akan muncul sebagai opsi saat Anda menambahkan Siswa.</li>
              </ol>

              <h5 class="mt-4"><i class="fas fa-user-tie text-success"></i> Guru & Tenaga Kependidikan (GTK)</h5>
              <p>Anda bisa menarik data GTK dari Dapodik atau menambahkannya secara manual:</p>
              <ol>
                <li>Akses menu <strong>Data Master > Guru & Tendik</strong>.</li>
                <li>Klik tombol <strong>Tambah GTK</strong>.</li>
                <li>Isi NIP/NUPTK, Nama, Jabatan, dan data pendukung lainnya.</li>
                <li>Guru yang terdaftar di sini dapat dipilih sebagai Wali Kelas.</li>
              </ol>

              <h5 class="mt-4"><i class="fas fa-users-cog text-warning"></i> Operator Sekolah</h5>
              <p>Sebagai Admin Utama Lembaga, Anda dapat mendelegasikan tugas ke staf lain:</p>
              <ol>
                <li>Akses menu <strong>Data Master > Operator Sekolah</strong>.</li>
                <li>Klik <strong>Tambah Operator</strong> dan buatkan akun login (Email & Password).</li>
                <li>Operator ini bisa masuk ke EduArchive dan membantu mengelola data kesiswaan atau dokumen.</li>
              </ol>
            </div>

            {{-- KESISWAAN TAB --}}
            <div class="tab-pane fade" id="kesiswaan" role="tabpanel">
              <h4 class="text-primary"><i class="fas fa-user-graduate"></i> Manajemen Kesiswaan & Lulusan</h4>
              <hr>

              <h5><i class="fas fa-users text-primary"></i> Mengelola Siswa Aktif</h5>
              <ul>
                <li>Buka menu <strong>Kesiswaan > Data Siswa Aktif</strong>.</li>
                <li>Untuk menambah data tunggal, klik tombol <span class="badge badge-primary"><i class="fas fa-plus"></i> Tambah Siswa</span>.</li>
                <li><strong>Naik Kelas Massal:</strong> Pilih tombol <strong>Kenaikan Kelas</strong>, pilih kelas asal dan kelas tujuan, lalu proses pindah kelas untuk satu rombongan belajar sekaligus.</li>
              </ul>

              <h5 class="mt-4"><i class="fas fa-user-check text-success"></i> Meluluskan Siswa & Data Lulusan</h5>
              <ul>
                <li>Dari halaman <strong>Siswa Aktif</strong>, pilih siswa kelas tingkat akhir.</li>
                <li>Klik opsi <strong>Kelulusan</strong>, masukkan tahun lulus, lalu konfirmasi.</li>
                <li>Siswa yang diluluskan akan otomatis dipindahkan ke menu <strong>Data Siswa Lulusan</strong>.</li>
                <li>Di Data Lulusan, Anda bisa melacak jejak alumni (apakah mereka lanjut studi atau bekerja).</li>
              </ul>

              <h5 class="mt-4"><i class="fas fa-folder-open text-warning"></i> Arsip Dokumen Siswa</h5>
              <ol>
                <li>Buka menu <strong>Kesiswaan > Dokumen Siswa</strong>.</li>
                <li>Klik <strong>Upload Dokumen</strong>.</li>
                <li>Pilih nama siswa, lalu pilih jenis dokumen yang akan diarsip (misal: Ijazah, KK, Akta Kelahiran).</li>
                <li>Upload file PDF atau Gambar. Dokumen ini akan tersimpan permanen secara digital.</li>
              </ol>

              <h5 class="mt-4"><i class="fas fa-hand-holding-usd text-info"></i> Data PIP (Program Indonesia Pintar)</h5>
              <p>Kelola data pencairan bantuan siswa secara rapi melalui menu <strong>Data PIP</strong>. Anda bisa mencatat nominal bantuan, tanggal pencairan, serta mengunggah bukti pencairan.</p>
            </div>

            {{-- SARANA TAB --}}
            <div class="tab-pane fade" id="sarana" role="tabpanel">
              <h4 class="text-primary"><i class="fas fa-building"></i> Sarana & Administrasi Lembaga</h4>
              <hr>

              <h5><i class="fas fa-file-archive text-primary"></i> Arsip Lembaga</h5>
              <p>Simpan dokumen legal dan administratif sekolah Anda di cloud:</p>
              <ul>
                <li>SK Pendirian Sekolah</li>
                <li>Sertifikat Akreditasi</li>
                <li>NPSN / Izin Operasional</li>
              </ul>

              <h5 class="mt-4"><i class="fas fa-tools text-success"></i> RKB & REHAB (Infrastruktur)</h5>
              <p>Kelola pencatatan ruang kelas baru (RKB) atau rehabilitasi gedung. Anda dapat menyimpan data pengerjaan, anggaran, persentase progres, dan dokumentasi foto proyek.</p>

              <h5 class="mt-4"><i class="fas fa-calendar-alt text-warning"></i> Kegiatan Belajar</h5>
              <p>Catat jadwal akademik, jurnal mengajar guru, atau log kegiatan harian sekolah secara terpusat untuk keperluan monev (monitoring dan evaluasi).</p>

              <h5 class="mt-4"><i class="fas fa-file-signature text-danger"></i> Fakta Integritas</h5>
              <p>Dokumentasikan file Fakta Integritas yang wajib ditandatangani oleh Guru, Kepala Sekolah, atau Tenaga Kependidikan setiap tahun ajaran baru.</p>
            </div>

            {{-- SETTINGS TAB --}}
            <div class="tab-pane fade" id="settings" role="tabpanel">
              <h4 class="text-primary"><i class="fas fa-cogs"></i> Pengaturan Sistem & Profil</h4>
              <hr>

              <h5><i class="fas fa-school text-info"></i> Pengaturan Sekolah</h5>
              <p>Di menu ini, lengkapi identitas utama sekolah (Nama, Alamat, Email, No. Telp, dll). Pastikan Anda mengisi nama Kepala Sekolah berserta NIP untuk keperluan kop surat otomatis.</p>

              <h5 class="mt-4"><i class="fas fa-image text-success"></i> Profil Publik (Branding)</h5>
              <p>Menu <strong>Pengaturan Sistem > Profil Publik</strong> digunakan untuk mengatur bagaimana sekolah Anda ditampilkan ke masyarakat umum atau portal dinas.</p>
              <ul>
                <li><strong>Logo Sekolah:</strong> Upload logo transparan untuk kop dan halaman depan.</li>
                <li><strong>Kop Surat:</strong> Upload logo daerah dan tanda tangan digital kepala sekolah (opsional).</li>
                <li><strong>Cover/Banner:</strong> Gambar utama untuk landing page sekolah Anda.</li>
              </ul>

              <h5 class="mt-4"><i class="fas fa-project-diagram text-warning"></i> Web Service API (Dapodik)</h5>
              <ol>
                <li>Untuk menarik data dari Dapodik, Anda butuh konfigurasi API.</li>
                <li>Masuk ke menu <strong>Pengaturan Sistem > Web Service API</strong>.</li>
                <li>Masukkan <strong>IP Address / URL Aplikasi Dapodik Lokal</strong> (contoh: http://localhost:5774).</li>
                <li>Masukkan <strong>Token API</strong> yang didapat dari pengaturan Web Service Dapodik lokal Anda.</li>
                <li>Klik Simpan dan Tes Koneksi.</li>
              </ol>
            </div>

            {{-- FAQ TAB --}}
            <div class="tab-pane fade" id="faq" role="tabpanel">
              <h4 class="text-primary"><i class="fas fa-question-circle"></i> Pertanyaan yang Sering Diajukan (FAQ)</h4>
              <hr>

              <div class="card card-outline card-info collapsed-card">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-question"></i> Tarik data Dapodik gagal (Timeout / Connection Refused). Apa solusinya?</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  Pastikan laptop/PC tempat aplikasi Dapodik terinstall sedang menyala, aplikasi Dapodik bisa dibuka, dan IP URL Web Service (contoh: http://localhost:5774) dapat diakses dari jaringan internet server EduArchive (bisa menggunakan aplikasi bantuan seperti Ngrok jika Anda menggunakan jaringan lokal).
                </div>
              </div>

              <div class="card card-outline card-info collapsed-card">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-question"></i> Kenapa data siswa tidak muncul di fitur Kenaikan Kelas?</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  Pastikan Anda sudah mengelompokkan siswa tersebut ke dalam Rombel/Kelas. Siswa tanpa kelas (unassigned) tidak dapat dinaikkan kelasnya secara massal. Edit data siswa tersebut dan assign ke kelas asal terlebih dahulu.
                </div>
              </div>

              <div class="card card-outline card-info collapsed-card">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-question"></i> Apakah data aman? Siapa yang bisa melihat dokumen arsip?</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  Data bersifat terisolasi. Fitur Multi-Tenant EduArchive menjamin file dan database lembaga Anda tidak bisa diakses oleh sekolah lain. Hanya Admin Sekolah, Operator, dan (jika diizinkan) Dinas Pendidikan setempat yang memiliki hak akses untuk memonitor arsip tersebut.
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection