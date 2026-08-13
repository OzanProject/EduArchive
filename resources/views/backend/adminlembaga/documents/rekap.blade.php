@extends('backend.layouts.app')

@section('title', 'Rekap Dokumen')
@section('page_title', 'Rekap Kelengkapan Dokumen')

@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('adminlembaga.dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Rekap Dokumen</li>
@endsection

@push('styles')
<style>
  /* ============================================================
     REKAP DOKUMEN — Premium UI
  ============================================================ */

  .rekap-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }
  @media (max-width: 992px) { .rekap-cards { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 576px)  { .rekap-cards { grid-template-columns: 1fr; } }

  .rekap-card {
    border-radius: 14px;
    padding: 20px 22px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.07);
    background: #fff;
    border: 1px solid rgba(0,0,0,0.06);
    transition: transform 0.2s, box-shadow 0.2s;
  }
  .rekap-card:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,0.1); }

  .rekap-card-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
  }
  .rekap-card-icon.blue   { background: #EBF4FF; color: #3B82F6; }
  .rekap-card-icon.green  { background: #ECFDF5; color: #10B981; }
  .rekap-card-icon.red    { background: #FEF2F2; color: #EF4444; }
  .rekap-card-icon.orange { background: #FFF7ED; color: #F97316; }

  .rekap-card-value {
    font-size: 26px; font-weight: 700; color: #1e293b; line-height: 1;
  }
  .rekap-card-label {
    font-size: 12px; color: #64748b; margin-top: 4px; font-weight: 500;
  }

  /* Filter Bar */
  .rekap-filter-bar {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  }

  /* Table header */
  .rekap-table-wrap table thead th {
    background: #1e293b;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    padding: 11px 8px;
    white-space: nowrap;
    border: none;
    vertical-align: middle;
  }
  .rekap-table-wrap table tbody tr {
    transition: background 0.15s;
  }
  .rekap-table-wrap table tbody tr:hover { background: #f8faff; }
  .rekap-table-wrap table tbody td {
    padding: 9px 8px;
    vertical-align: middle;
    border-color: #f0f0f0;
    font-size: 13px;
  }

  /* Doc badge */
  .doc-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 26px; height: 26px; border-radius: 50%; font-size: 12px;
  }
  .doc-badge.has  { background: #ECFDF5; color: #10B981; }
  .doc-badge.none { background: #FEF2F2; color: #EF4444; }

  /* Progress mini */
  .progress-rekap {
    height: 5px; border-radius: 3px; background: #e9ecef; overflow: hidden;
    margin-top: 3px;
  }
  .progress-rekap-fill.full  { height: 100%; background: #10B981; border-radius: 3px; }
  .progress-rekap-fill.mid   { height: 100%; background: #F97316; border-radius: 3px; }
  .progress-rekap-fill.low   { height: 100%; background: #EF4444; border-radius: 3px; }

  .pill-aktif  { background: #ECFDF5; color: #065f46; border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 600; }
  .pill-lulus  { background: #EFF6FF; color: #1e40af; border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 600; }

  .btn-print-rekap {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff; border: none; border-radius: 8px;
    padding: 8px 18px; font-size: 13px; font-weight: 600;
    box-shadow: 0 4px 14px rgba(99,102,241,0.35);
    transition: all 0.2s;
    display: inline-flex; align-items: center; gap: 6px;
    text-decoration: none;
  }
  .btn-print-rekap:hover {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff; transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(99,102,241,0.45);
    text-decoration: none;
  }
</style>
@endpush

@section('content')

  {{-- Summary Cards --}}
  <div class="rekap-cards">
    <div class="rekap-card">
      <div class="rekap-card-icon blue"><i class="fas fa-users"></i></div>
      <div>
        <div class="rekap-card-value">{{ $totalSiswa }}</div>
        <div class="rekap-card-label">Total Siswa</div>
      </div>
    </div>
    <div class="rekap-card">
      <div class="rekap-card-icon green"><i class="fas fa-check-circle"></i></div>
      <div>
        <div class="rekap-card-value">{{ $totalLengkap }}</div>
        <div class="rekap-card-label">Dokumen Lengkap</div>
      </div>
    </div>
    <div class="rekap-card">
      <div class="rekap-card-icon red"><i class="fas fa-times-circle"></i></div>
      <div>
        <div class="rekap-card-value">{{ $totalBelum }}</div>
        <div class="rekap-card-label">Belum Lengkap</div>
      </div>
    </div>
    <div class="rekap-card">
      <div class="rekap-card-icon orange"><i class="fas fa-chart-pie"></i></div>
      <div>
        <div class="rekap-card-value">{{ $avgPercent }}%</div>
        <div class="rekap-card-label">Rata-rata Kelengkapan</div>
      </div>
    </div>
  </div>

  {{-- Filter Bar --}}
  <div class="rekap-filter-bar">
    <form method="GET" action="{{ route('adminlembaga.rekap_dokumen') }}" class="d-flex flex-wrap align-items-center" style="gap: 10px;">

      <div class="input-group input-group-sm" style="width: 200px;">
        <input type="text" name="search" class="form-control" placeholder="Cari nama / NISN..." value="{{ $search }}">
        <div class="input-group-append">
          <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
        </div>
      </div>

      <select name="status" class="form-control form-control-sm" style="width: 130px;" onchange="this.form.submit()">
        <option value="Aktif" {{ $status === 'Aktif' ? 'selected' : '' }}>Siswa Aktif</option>
        <option value="Lulus" {{ $status === 'Lulus' ? 'selected' : '' }}>Alumni/Lulus</option>
      </select>

      <select name="kelas" class="form-control form-control-sm" style="width: 140px;" onchange="this.form.submit()">
        <option value="">Semua Kelas</option>
        @foreach($kelasList as $k)
          <option value="{{ $k }}" {{ $kelasFilter == $k ? 'selected' : '' }}>{{ $k }}</option>
        @endforeach
      </select>

      <select name="doc_filter" class="form-control form-control-sm" style="width: 160px;" onchange="this.form.submit()">
        <option value="">Semua Status Dok.</option>
        <option value="lengkap" {{ $docFilter === 'lengkap' ? 'selected' : '' }}>Sudah Lengkap</option>
        <option value="belum"   {{ $docFilter === 'belum'   ? 'selected' : '' }}>Belum Lengkap</option>
      </select>

      <select name="per_page" class="form-control form-control-sm" style="width: 100px;" onchange="this.form.submit()">
        <option value="20"  {{ $perPage == 20  ? 'selected' : '' }}>20 Baris</option>
        <option value="50"  {{ $perPage == 50  ? 'selected' : '' }}>50 Baris</option>
        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 Baris</option>
      </select>

      @if($search || $kelasFilter || $docFilter)
        <a href="{{ route('adminlembaga.rekap_dokumen') }}" class="btn btn-sm btn-light border">
          <i class="fas fa-times mr-1"></i>Reset
        </a>
      @endif

      <div class="ml-auto">
        <a href="{{ route('adminlembaga.rekap_dokumen.print', ['status' => $status]) }}"
           target="_blank" class="btn-print-rekap">
          <i class="fas fa-print"></i> Cetak PDF
        </a>
      </div>
    </form>
  </div>

  {{-- Table --}}
  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between" style="padding: 14px 20px;">
      <h5 class="mb-0" style="font-size: 14px; font-weight: 700; color: #1e293b;">
        <i class="fas fa-clipboard-list mr-2 text-primary"></i>
        Rekap Kelengkapan Dokumen —
        <span class="{{ $status === 'Aktif' ? 'pill-aktif' : 'pill-lulus' }}">
          {{ $status === 'Aktif' ? 'Siswa Aktif' : 'Alumni/Lulus' }}
        </span>
      </h5>
      <small class="text-muted">{{ $paginated->total() }} siswa ditemukan</small>
    </div>
    <div class="card-body p-0">
      <div class="rekap-table-wrap">
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th style="width:40px;">No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                @foreach($docTypes as $dt)
                  <th style="max-width: 80px; white-space: normal; font-size: 10px; text-align:center;">
                    {{ $dt->name }}
                  </th>
                @endforeach
                <th style="width: 90px; text-align:center;">Kelengkapan</th>
              </tr>
            </thead>
            <tbody>
              @forelse($paginated as $student)
                @php
                  $pct = $student->doc_percent;
                  $fillClass = $pct >= 100 ? 'full' : ($pct >= 50 ? 'mid' : 'low');
                  $textColor  = $pct >= 100 ? '#10B981' : ($pct >= 50 ? '#F97316' : '#EF4444');
                @endphp
                <tr>
                  <td class="text-center" style="color:#6b7280; font-size:12px;">
                    {{ $paginated->firstItem() + $loop->index }}
                  </td>
                  <td><code style="font-size: 12px; color: #374151;">{{ $student->nisn }}</code></td>
                  <td style="font-weight: 600; color: #1e293b;">{{ $student->nama }}</td>
                  <td>
                    @if($student->kelas)
                      <span class="badge badge-secondary">{{ $student->kelas }}</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  @foreach($docTypes as $dt)
                    <td class="text-center">
                      @if($student->doc_status[$dt->name] ?? false)
                        <span class="doc-badge has" title="Sudah — {{ $dt->name }}">
                          <i class="fas fa-check"></i>
                        </span>
                      @else
                        <span class="doc-badge none" title="Belum — {{ $dt->name }}">
                          <i class="fas fa-times"></i>
                        </span>
                      @endif
                    </td>
                  @endforeach
                  <td class="text-center">
                    <span style="font-size: 13px; font-weight: 700; color: {{ $textColor }};">
                      {{ $student->doc_filled }}/{{ $student->doc_total }}
                    </span>
                    <div class="progress-rekap">
                      <div class="progress-rekap-fill {{ $fillClass }}" style="width: {{ $pct }}%;"></div>
                    </div>
                    <small style="font-size: 10px; color: {{ $textColor }};">{{ $pct }}%</small>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="{{ 4 + $docTypes->count() + 1 }}" class="text-center py-5">
                    <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                    <span class="text-muted">Tidak ada data siswa ditemukan.</span>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
    @if($paginated->hasPages())
      <div class="card-footer clearfix">
        {{ $paginated->withQueryString()->links('pagination::bootstrap-4') }}
      </div>
    @endif
  </div>

@endsection
