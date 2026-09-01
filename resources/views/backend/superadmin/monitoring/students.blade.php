@extends('backend.layouts.app')

@section('title', 'Data Siswa')
@section('page_title', 'Data Siswa - ' . $tenant->nama_sekolah)

@push('styles')
<style>
  /* ============================================================
     MODAL PINDAH LEMBAGA — Premium UI
  ============================================================ */

  /* Overlay backdrop lebih gelap & blur */
  .move-modal .modal-backdrop { backdrop-filter: blur(4px); }

  /* Kontainer modal */
  .move-modal-content {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 25px 60px rgba(0,0,0,0.25), 0 8px 20px rgba(0,0,0,0.12);
  }

  /* ---- Header ---- */
  .move-modal-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 22px 24px;
    background: linear-gradient(135deg, #f6a623 0%, #e8890e 100%);
    position: relative;
  }
  .move-modal-header-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #fff;
    flex-shrink: 0;
  }
  .move-modal-title {
    margin: 0;
    font-size: 17px;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
  }
  .move-modal-subtitle {
    margin: 2px 0 0;
    font-size: 12px;
    color: rgba(255,255,255,0.85);
  }
  .move-modal-close {
    position: absolute;
    right: 18px;
    top: 18px;
    background: rgba(255,255,255,0.2);
    border: none;
    border-radius: 8px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.2s;
    padding: 0;
  }
  .move-modal-close:hover { background: rgba(255,255,255,0.35); }

  /* ---- Body ---- */
  .move-modal-body {
    padding: 24px;
    background: #fafbfc;
  }

  /* Info Siswa */
  .move-student-info {
    display: flex;
    align-items: center;
    gap: 14px;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  }
  .move-student-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 20px;
    flex-shrink: 0;
  }
  .move-student-name {
    display: block;
    font-size: 15px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 4px;
  }
  .move-student-meta {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }
  .move-student-meta span {
    font-size: 12px;
    color: #718096;
  }

  /* Asal Lembaga */
  .move-from-info {
    background: #fff;
    border: 1px solid #e9ecef;
    border-left: 4px solid #6c757d;
    border-radius: 10px;
    padding: 12px 16px;
    margin-bottom: 4px;
  }
  .move-from-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    margin-bottom: 4px;
  }
  .move-from-name {
    font-size: 14px;
    font-weight: 600;
    color: #2d3748;
  }

  /* Arrow */
  .move-arrow-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    margin: 6px 0;
  }
  .move-arrow-line {
    width: 2px;
    height: 10px;
    background: #dee2e6;
  }
  .move-arrow-icon {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #f6a623;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    box-shadow: 0 2px 8px rgba(246,166,35,0.4);
  }

  /* Select Lembaga Tujuan */
  .move-select-group { margin-bottom: 16px; }
  .move-select-label {
    font-size: 13px;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
    display: block;
  }
  .move-select-wrapper {
    position: relative;
  }
  .move-select {
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 10px !important;
    padding: 10px 40px 10px 14px !important;
    font-size: 14px !important;
    height: auto !important;
    background-color: #fff !important;
    color: #2d3748 !important;
    cursor: pointer;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .move-select:focus {
    border-color: #f6a623 !important;
    box-shadow: 0 0 0 3px rgba(246,166,35,0.15) !important;
    outline: none;
  }
  .move-select-icon {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    pointer-events: none;
    font-size: 12px;
  }

  /* Warning Box */
  .move-warning-box {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 10px;
    padding: 12px 14px;
  }
  .move-warning-icon {
    color: #d97706;
    font-size: 16px;
    flex-shrink: 0;
    margin-top: 1px;
  }
  .move-warning-text {
    font-size: 12.5px;
    color: #92400e;
    line-height: 1.5;
  }

  /* ---- Footer ---- */
  .move-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 24px;
    background: #fff;
    border-top: 1px solid #f0f0f0;
  }
  .move-btn-cancel {
    padding: 9px 20px;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 600;
    background: #f1f3f5;
    color: #4a5568;
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
  }
  .move-btn-cancel:hover {
    background: #e9ecef;
    color: #2d3748;
  }
  .move-btn-confirm {
    padding: 9px 22px;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 600;
    background: linear-gradient(135deg, #f6a623 0%, #e8890e 100%);
    color: #fff;
    border: none;
    box-shadow: 0 4px 14px rgba(246,166,35,0.4);
    transition: all 0.2s;
  }
  .move-btn-confirm:hover {
    background: linear-gradient(135deg, #e8980e 0%, #d47a00 100%);
    box-shadow: 0 6px 18px rgba(246,166,35,0.5);
    transform: translateY(-1px);
    color: #fff;
  }
  .move-btn-confirm:active { transform: translateY(0); }
</style>
@endpush

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('superadmin.monitoring.index') }}">Monitoring</a></li>
  <li class="breadcrumb-item active">{{ $tenant->nama_sekolah }}</li>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center" style="gap: 10px;">
            <h3 class="card-title mb-2 mb-md-0">Daftar 
              @if(request('status') == 'lulus')
                Alumni
              @elseif(request('status') == 'keluar')
                Siswa Mutasi/Keluar
              @else
                Siswa Aktif
              @endif
              di {{ $tenant->nama_sekolah }}
            </h3>
            <div class="card-tools d-flex flex-wrap align-items-center" style="gap: 10px;">
              <form action="{{ route('superadmin.monitoring.school', $tenant->id) }}" method="GET"
                class="d-flex flex-wrap align-items-center m-0" style="gap: 10px;">
                <div class="input-group input-group-sm" style="max-width: 140px;">
                  <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="aktif" {{ request('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Siswa Aktif</option>
                    <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Alumni</option>
                    <option value="keluar" {{ request('status') == 'keluar' ? 'selected' : '' }}>Mutasi/Keluar</option>
                  </select>
                </div>
                <div class="input-group input-group-sm" style="width: 200px;">
                  <input type="text" name="table_search" class="form-control float-right" placeholder="Cari Siswa..."
                    value="{{ request('table_search') }}">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>

                @if(request('status') == 'lulus')
                  <div class="input-group input-group-sm">
                    <select name="year" class="form-control" onchange="this.form.submit()">
                      <option value="">Semua Tahun</option>
                      @foreach($graduation_years as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                      @endforeach
                    </select>
                  </div>
                @endif

                {{-- Filter Jumlah Baris --}}
                <div class="input-group input-group-sm" style="max-width: 100px;">
                  <select name="per_page" class="form-control" onchange="this.form.submit()">
                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 Baris</option>
                    <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30 Baris</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                  </select>
                </div>

                {{-- Filter Usia --}}
                <div class="input-group input-group-sm">
                  <select name="age_filter" class="form-control" onchange="this.form.submit()">
                    <option value="">Semua Usia</option>
                    <option value="under_25" {{ request('age_filter') == 'under_25' ? 'selected' : '' }}>Usia &lt; 25 Tahun</option>
                    <option value="over_25" {{ request('age_filter') == 'over_25' ? 'selected' : '' }}>Usia &ge; 25 Tahun</option>
                  </select>
                </div>
              </form>

              <div class="d-flex" style="gap: 5px;">
                <form action="{{ route('superadmin.monitoring.verify_all_documents', ['id' => $tenant->id, 'status' => request('status', 'aktif'), 'year' => request('year'), 'age_filter' => request('age_filter')]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menyetujui secara massal dokumen siswa yang SESUAI FILTER SAAT INI?');">
                  @csrf
                  <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center">
                    <i class="fas fa-check-double mr-1"></i> Verifikasi Semua
                  </button>
                </form>
                <form action="{{ route('superadmin.monitoring.cancel_verify_all_documents', ['id' => $tenant->id, 'status' => request('status', 'aktif'), 'year' => request('year'), 'age_filter' => request('age_filter')]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin MEMBATALKAN verifikasi massal untuk dokumen siswa yang SESUAI FILTER SAAT INI?');">
                  @csrf
                  <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center">
                    <i class="fas fa-times-circle mr-1"></i> Batal Verif
                  </button>
                </form>
                <button type="button" class="btn btn-warning btn-sm d-flex align-items-center" data-toggle="modal" data-target="#massMoveNoDocsModal" title="Mutasi Massal Siswa Tanpa Dokumen">
                  <i class="fas fa-truck-moving mr-1"></i> Mutasi 0 Dokumen
                </button>
                <a href="{{ route('superadmin.monitoring.export_excel', ['id' => $tenant->id, 'status' => request('status', 'aktif'), 'year' => request('year'), 'age_filter' => request('age_filter')]) }}"
                  class="btn btn-success btn-sm d-flex align-items-center">
                  <i class="fas fa-file-excel mr-1"></i> Export Excel
                </a>
                <a href="{{ route('superadmin.monitoring.export_pdf', ['id' => $tenant->id, 'status' => request('status', 'aktif'), 'year' => request('year'), 'age_filter' => request('age_filter')]) }}"
                  class="btn btn-danger btn-sm d-flex align-items-center">
                  <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                </a>
                <a href="{{ route('superadmin.monitoring.print_recap', ['id' => $tenant->id, 'status' => request('status', 'aktif'), 'year' => request('year'), 'age_filter' => request('age_filter')]) }}"
                  target="_blank" class="btn btn-warning btn-sm d-flex align-items-center">
                  <i class="fas fa-print mr-1"></i> Cetak Rekap
                </a>
                <a href="{{ route('superadmin.monitoring.index', ['category' => request('status') == 'lulus' ? 'graduates' : 'students']) }}"
                  class="btn btn-secondary btn-sm d-flex align-items-center">
                  <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>L/P</th>
                <th>Kelas</th>
                <th>Tgl Lahir</th>
                <th>Usia</th>
                <th>Status Verifikasi</th>
                @foreach($docTypes as $type)
                  <th class="text-center">{{ $type->name }}</th>
                @endforeach
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($students as $student)
                <tr>
                  <td>{{ $students->firstItem() + $loop->index }}</td>
                  <td>{{ $student->nisn ?? '-' }}</td>
                  <td>{{ $student->nama }}</td>
                  <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                  <td>{{ $student->kelas ?? '-' }}</td>
                  <td>{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '-' }}</td>
                  <td>
                    @if($student->birth_date)
                      {{ \Carbon\Carbon::parse($student->birth_date)->age }} tahun
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    @php
                        $approvedTypes = $student->documents->where('validation_status', 'approved')->pluck('document_type')->toArray();
                        $isVerified = true;
                        foreach ($required_types as $req) {
                            if (!in_array($req, $approvedTypes)) {
                                $isVerified = false;
                                break;
                            }
                        }
                    @endphp
                    @if($isVerified)
                      <span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle mr-1"></i> Terverifikasi</span>
                    @else
                      <span class="badge badge-danger px-3 py-2"><i class="fas fa-times-circle mr-1"></i> Belum Lengkap</span>
                    @endif
                  </td>
                  @foreach($docTypes as $type)
                    @php
                        $hasDoc = $student->documents->where('validation_status', 'approved')->contains('document_type', $type->name);
                    @endphp
                    <td class="text-center">
                      @if($hasDoc)
                        <span class="text-success" title="Sudah Upload" data-toggle="tooltip"><i class="fas fa-check"></i></span>
                      @else
                        <span class="text-danger" title="Belum Upload" data-toggle="tooltip"><i class="fas fa-times"></i></span>
                      @endif
                    </td>
                  @endforeach
                  <td class="text-center">
                    <a href="{{ route('superadmin.monitoring.student', ['tenant_id' => $tenant->id, 'id' => $student->id]) }}"
                      class="btn btn-info btn-sm" title="Detail Siswa & Dokumen">
                      <i class="fas fa-search"></i> Detail
                    </a>
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#moveModal{{ $student->id }}" title="Pindah Lembaga">
                      <i class="fas fa-exchange-alt"></i> Pindah
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#incomingMoveModal{{ $student->id }}" title="Mutasi Masuk">
                      <i class="fas fa-sign-in-alt"></i> Mutasi Masuk
                    </button>
                    <form action="{{ route('superadmin.monitoring.student.set_inactive', ['tenant_id' => $tenant->id, 'id' => $student->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status siswa ini menjadi Keluar/Mutasi Tanpa Keterangan?');">
                      @csrf
                      <button type="submit" class="btn btn-secondary btn-sm" title="Set Inaktif">
                        <i class="fas fa-user-times"></i> Set Inaktif
                      </button>
                    </form>
                    <form action="{{ route('superadmin.monitoring.student.delete', ['tenant_id' => $tenant->id, 'id' => $student->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa {{ addslashes($student->nama) }} beserta seluruh dokumennya? Tindakan ini tidak dapat dibatalkan.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm" title="Hapus Siswa">
                        <i class="fas fa-trash"></i> Hapus
                      </button>
                    </form>

                    <!-- Modal Mutasi Masuk -->
                    <div class="modal fade move-modal" id="incomingMoveModal{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="incomingMoveModalLabel{{ $student->id }}" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content move-modal-content">
                          <form action="{{ route('superadmin.monitoring.student.incoming_move', ['tenant_id' => $tenant->id, 'id' => $student->id]) }}" method="POST">
                            @csrf

                            {{-- Header --}}
                            <div class="move-modal-header bg-primary">
                              <div class="move-modal-header-icon text-primary">
                                <i class="fas fa-sign-in-alt"></i>
                              </div>
                              <h5 class="modal-title font-weight-bold" id="incomingMoveModalLabel{{ $student->id }}">Mutasi Masuk Siswa</h5>
                              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>

                            {{-- Body --}}
                            <div class="modal-body p-4 text-left">
                              <p class="mb-4 text-muted">
                                Catat siswa <strong>{{ $student->nama }}</strong> (NISN: {{ $student->nisn ?? '-' }}) sebagai siswa pindahan dari sekolah lain ke lembaga ini.
                              </p>

                              <div class="form-group">
                                <label class="font-weight-bold">Asal Sekolah <span class="text-danger">*</span></label>
                                <select name="from_tenant_id" class="form-control select2bs4" style="width: 100%;" required>
                                  <option value="">-- Pilih Asal Sekolah --</option>
                                  @foreach($all_tenants as $t)
                                    <option value="{{ $t->id }}">{{ $t->nama_sekolah }} (NPSN: {{ $t->npsn }})</option>
                                  @endforeach
                                </select>
                                <small class="text-muted mt-2 d-block">
                                  <i class="fas fa-info-circle"></i> Memilih sekolah asal akan mencatat riwayat mutasi masuk untuk siswa ini.
                                </small>
                              </div>
                            </div>

                            {{-- Footer --}}
                            <div class="modal-footer bg-light border-top-0 rounded-bottom">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-primary shadow-sm">
                                <i class="fas fa-save mr-1"></i> Simpan Mutasi Masuk
                              </button>
                            </div>

                          </form>
                        </div>
                      </div>
                    </div>

                    <!-- Modal Pindah Lembaga -->
                    <div class="modal fade move-modal" id="moveModal{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="moveModalLabel{{ $student->id }}" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content move-modal-content">
                          <form action="{{ route('superadmin.monitoring.student.move', ['tenant_id' => $tenant->id, 'id' => $student->id]) }}" method="POST">
                            @csrf

                            {{-- Header --}}
                            <div class="move-modal-header">
                              <div class="move-modal-header-icon">
                                <i class="fas fa-exchange-alt"></i>
                              </div>
                              <div>
                                <h5 class="move-modal-title">Pindah Lembaga</h5>
                                <p class="move-modal-subtitle">Transfer data siswa ke lembaga lain</p>
                              </div>
                              <button type="button" class="move-modal-close" data-dismiss="modal" aria-label="Close">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>

                            {{-- Body --}}
                            <div class="move-modal-body">

                              {{-- Info Siswa --}}
                              <div class="move-student-info">
                                <div class="move-student-avatar">
                                  <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="move-student-details">
                                  <span class="move-student-name">{{ $student->nama }}</span>
                                  <div class="move-student-meta">
                                    <span><i class="fas fa-id-card mr-1"></i>NISN: {{ $student->nisn }}</span>
                                    <span><i class="fas fa-chalkboard mr-1"></i>Kelas: {{ $student->kelas }}</span>
                                  </div>
                                </div>
                              </div>

                              {{-- Asal Lembaga --}}
                              <div class="move-from-info">
                                <div class="move-from-label">
                                  <i class="fas fa-school mr-1"></i> Lembaga Asal
                                </div>
                                <div class="move-from-name">{{ $tenant->nama_sekolah }}</div>
                              </div>

                              {{-- Arrow --}}
                              <div class="move-arrow-wrapper">
                                <div class="move-arrow-line"></div>
                                <div class="move-arrow-icon"><i class="fas fa-arrow-down"></i></div>
                                <div class="move-arrow-line"></div>
                              </div>

                              {{-- Tujuan Lembaga --}}
                              <div class="form-group move-select-group">
                                <label class="move-select-label" for="target_tenant_id_{{ $student->id }}">
                                  <i class="fas fa-map-marker-alt mr-1"></i> Lembaga Tujuan
                                  <span class="text-danger">*</span>
                                </label>
                                <div class="move-select-wrapper">
                                  <select name="target_tenant_id" id="target_tenant_id_{{ $student->id }}" class="form-control move-select" required>
                                    <option value="">-- Pilih Lembaga Tujuan --</option>
                                    @foreach($all_tenants as $t)
                                      <option value="{{ $t->id }}">{{ $t->nama_sekolah }} (NPSN: {{ $t->npsn }})</option>
                                    @endforeach
                                  </select>
                                  <div class="move-select-icon"><i class="fas fa-chevron-down"></i></div>
                                </div>
                              </div>

                              {{-- Warning --}}
                              <div class="move-warning-box">
                                <i class="fas fa-exclamation-triangle move-warning-icon"></i>
                                <div class="move-warning-text">
                                  <strong>Perhatian!</strong> Semua dokumen dan riwayat kelulusan siswa ini akan ikut dipindahkan. Tindakan ini tidak dapat dibatalkan.
                                </div>
                              </div>

                            </div>

                            {{-- Footer --}}
                            <div class="move-modal-footer">
                              <button type="button" class="btn move-btn-cancel" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> Batal
                              </button>
                              <button type="submit" class="btn move-btn-confirm">
                                <i class="fas fa-paper-plane mr-1"></i> Pindahkan Sekarang
                              </button>
                            </div>

                          </form>
                        </div>
                      </div>
                    </div>
                    <!-- /End Modal Pindah Lembaga -->

                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">Tidak ada data siswa ditemukan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        @if($students->hasPages())
        <div class="card-footer clearfix">
          {{ $students->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
        @endif
      </div>
      <!-- /.card -->
    </div>
  </div>

  <!-- Modal Mutasi Massal Tanpa Dokumen -->
  <div class="modal fade" id="massMoveNoDocsModal" tabindex="-1" role="dialog" aria-labelledby="massMoveNoDocsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="{{ route('superadmin.monitoring.school.mass_move_nodocs', $tenant->id) }}" method="POST">
          @csrf
          <div class="modal-header bg-warning">
            <h5 class="modal-title font-weight-bold" id="massMoveNoDocsModalLabel">
              <i class="fas fa-truck-moving mr-1"></i> Mutasi Massal (0 Dokumen)
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body p-4 text-left">
            <p class="mb-4 text-muted">
              Pindahkan <strong>semua siswa yang sama sekali belum mengunggah dokumen</strong> ke lembaga tujuan (misal: sekolah penampungan/dummy).
            </p>
            <div class="form-group">
              <label class="font-weight-bold">Pilih Lembaga Tujuan <span class="text-danger">*</span></label>
              <select name="target_tenant_id" class="form-control select2bs4" style="width: 100%;" required>
                <option value="">-- Pilih Lembaga Tujuan --</option>
                @foreach($all_tenants as $t)
                  <option value="{{ $t->id }}">{{ $t->nama_sekolah }} (NPSN: {{ $t->npsn }})</option>
                @endforeach
              </select>
              <small class="text-muted mt-2 d-block">
                <i class="fas fa-exclamation-triangle text-warning"></i> Tindakan ini tidak dapat dibatalkan secara massal. Pastikan tidak ada siswa baru yang tak sengaja terpindah.
              </small>
            </div>
          </div>
          <div class="modal-footer bg-light border-top-0 rounded-bottom">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning shadow-sm font-weight-bold" onclick="return confirm('PENTING: Apakah Anda yakin memindahkan SEMUA SISWA dengan 0 dokumen di sekolah ini?');">
              <i class="fas fa-paper-plane mr-1"></i> Pindahkan Sekarang
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection