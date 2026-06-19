@extends('backend.layouts.app')

@section('title', 'Web Service API')
@section('page_title', 'Pengaturan Web Service API')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Web Service API</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Buat Token Baru</h3>
        </div>
        <form action="{{ route('adminlembaga.api_tokens.store') }}" method="POST">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="token_name">Nama Aplikasi (Misal: e-Rapor, Website Sekolah)</label>
              <input type="text" class="form-control @error('token_name') is-invalid @enderror" id="token_name" name="token_name" placeholder="Masukkan nama aplikasi..." required>
              @error('token_name')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
            <p class="text-muted small">Token ini akan memiliki hak akses `read` untuk menarik data Siswa, Guru, dan Rombel (Kelas).</p>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-key"></i> Generate Token</button>
          </div>
        </form>
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Token Aktif</h3>
        </div>
        <div class="card-body p-0 table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Nama Aplikasi</th>
                <th>Terakhir Digunakan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($tokens as $token)
                <tr>
                  <td>{{ $token->name }}</td>
                  <td>{{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Belum pernah' }}</td>
                  <td>
                    <form action="{{ route('adminlembaga.api_tokens.destroy', $token->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mencabut akses token ini? Aplikasi yang menggunakannya tidak akan bisa menarik data lagi.')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Cabut</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-muted">Belum ada API token yang dibuat.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card">
        <div class="card-header bg-info">
          <h3 class="card-title"><i class="fas fa-book"></i> Dokumentasi API (Panduan Integrasi)</h3>
        </div>
        <div class="card-body">
          <p>Fitur Web Service ini memungkinkan aplikasi pihak ketiga (seperti e-Rapor, SIAKAD, atau website sekolah) untuk menarik data dari server EduArchive.</p>
          <hr>
          <h5>1. Autentikasi (Bearer Token)</h5>
          <p>Setiap request API harus menyertakan Header <code>Authorization</code> dengan value <code>Bearer {TOKEN_ANDA}</code>.</p>
          <pre class="bg-dark text-light p-3 rounded"><code>GET /api/v1/students
Authorization: Bearer 1|abcdefg123456789...</code></pre>
          
          <h5 class="mt-4">2. Endpoint (Base URL: <code>{{ url('/') }}</code>)</h5>
          
          <div class="accordion" id="apiAccordion">
            <!-- Data Siswa -->
            <div class="card card-primary card-outline">
              <a class="d-block w-100" data-toggle="collapse" href="#apiStudents">
                <div class="card-header">
                  <h4 class="card-title w-100">
                    <span class="badge badge-success">GET</span> /api/v1/students
                  </h4>
                </div>
              </a>
              <div id="apiStudents" class="collapse" data-parent="#apiAccordion">
                <div class="card-body">
                  <p>Mendapatkan daftar data siswa. Data yang dikembalikan meliputi NISM, NISN, Nama Lengkap, Jenis Kelamin, Kelas, dll.</p>
                </div>
              </div>
            </div>

            <!-- Data Guru -->
            <div class="card card-primary card-outline">
              <a class="d-block w-100" data-toggle="collapse" href="#apiTeachers">
                <div class="card-header">
                  <h4 class="card-title w-100">
                    <span class="badge badge-success">GET</span> /api/v1/teachers
                  </h4>
                </div>
              </a>
              <div id="apiTeachers" class="collapse" data-parent="#apiAccordion">
                <div class="card-body">
                  <p>Mendapatkan daftar data guru (Tenaga Pendidik). Data yang dikembalikan meliputi NIP, Nama, Jabatan, dll.</p>
                </div>
              </div>
            </div>

            <!-- Data Rombel -->
            <div class="card card-primary card-outline">
              <a class="d-block w-100" data-toggle="collapse" href="#apiRombel">
                <div class="card-header">
                  <h4 class="card-title w-100">
                    <span class="badge badge-success">GET</span> /api/v1/classrooms
                  </h4>
                </div>
              </a>
              <div id="apiRombel" class="collapse" data-parent="#apiAccordion">
                <div class="card-body">
                  <p>Mendapatkan daftar Rombongan Belajar (Kelas). Data yang dikembalikan meliputi Nama Kelas, Wali Kelas, dan ID Kelas.</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
