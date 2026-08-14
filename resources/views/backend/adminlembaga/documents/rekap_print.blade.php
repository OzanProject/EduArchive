<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rekap Dokumen — {{ $printSettings['schoolName'] }}</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      color: #1a1a1a;
      background: #fff;
    }

    /* ---- Header ---- */
    .print-header {
      display: flex;
      align-items: center;
      gap: 16px;
      border-bottom: 3px solid #1e293b;
      padding-bottom: 12px;
      margin-bottom: 16px;
    }
    .print-logo {
      width: 70px; height: 70px;
      object-fit: contain;
      flex-shrink: 0;
    }
    .print-logo-placeholder {
      width: 70px; height: 70px;
      border: 2px solid #ddd;
      border-radius: 4px;
      display: flex; align-items: center; justify-content: center;
      font-size: 9px; color: #aaa; text-align: center;
      flex-shrink: 0;
    }
    .print-header-text { flex: 1; text-align: center; }
    .print-header-text .district { font-size: 11px; font-weight: 400; }
    .print-header-text .school-name { font-size: 15px; font-weight: 700; text-transform: uppercase; margin: 2px 0; }
    .print-header-text .school-meta { font-size: 10px; color: #444; }

    .print-title-box {
      text-align: center;
      background: #1e293b;
      color: #fff;
      padding: 8px 20px;
      border-radius: 4px;
      margin-bottom: 14px;
    }
    .print-title-box h2 { font-size: 13px; font-weight: 700; letter-spacing: 1px; }
    .print-title-box p  { font-size: 10px; margin-top: 2px; opacity: 0.85; }

    /* ---- Summary Row ---- */
    .summary-row {
      display: flex;
      gap: 12px;
      margin-bottom: 14px;
    }
    .summary-box {
      flex: 1;
      border: 1px solid #ddd;
      border-radius: 6px;
      padding: 8px 12px;
      text-align: center;
    }
    .summary-box .val { font-size: 18px; font-weight: 700; }
    .summary-box .lbl { font-size: 10px; color: #666; margin-top: 2px; }
    .summary-box.green .val { color: #16a34a; }
    .summary-box.red   .val { color: #dc2626; }
    .summary-box.blue  .val { color: #2563eb; }
    .summary-box.orange .val { color: #ea580c; }

    /* ---- Table ---- */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 4px;
    }
    thead th {
      background: #1e293b;
      color: #fff;
      font-size: 9px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      padding: 7px 5px;
      text-align: center;
      border: 1px solid #374151;
      vertical-align: middle;
    }
    thead th.text-left { text-align: left; }

    tbody td {
      border: 1px solid #e5e7eb;
      padding: 5px 5px;
      font-size: 10px;
      vertical-align: middle;
    }
    tbody tr:nth-child(even) { background: #f8fafc; }

    .check { color: #16a34a; font-weight: 700; text-align: center; }
    .cross  { color: #dc2626; font-weight: 700; text-align: center; }

    .pct-full   { color: #16a34a; font-weight: 700; }
    .pct-mid    { color: #ea580c; font-weight: 700; }
    .pct-low    { color: #dc2626; font-weight: 700; }

    /* ---- Footer ---- */
    .print-footer {
      margin-top: 20px;
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
    }
    .sign-box { text-align: center; }
    .sign-box .sign-title { font-size: 10px; font-weight: 700; }
    .sign-box .sign-space { height: 60px; }
    .sign-box .sign-name  { font-size: 11px; font-weight: 700; border-top: 1px solid #333; padding-top: 4px; }
    .sign-box .sign-nip   { font-size: 9px; color: #555; }

    .print-date {
      font-size: 10px; color: #555;
    }

    /* ---- Print controls ---- */
    .no-print {
      margin-bottom: 16px;
      display: flex; gap: 8px; align-items: center;
    }
    .btn-p {
      padding: 6px 16px; border-radius: 6px; font-size: 13px;
      font-weight: 600; cursor: pointer; border: none;
    }
    .btn-p.primary { background: #1e293b; color: #fff; }
    .btn-p.secondary { background: #e9ecef; color: #374151; }

    @media print {
      .no-print { display: none !important; }
      body { font-size: 10px; }
    }
  </style>
</head>
<body>

  <div class="no-print">
    <button class="btn-p primary" onclick="window.print()">
      🖨️ Cetak / Simpan PDF
    </button>
    <button class="btn-p secondary" onclick="window.close()">
      ✕ Tutup
    </button>
    <span style="font-size: 12px; color: #6b7280; margin-left: 8px;">
      Tips: Pilih "Save as PDF" di dialog cetak untuk menyimpan sebagai file PDF
    </span>
  </div>

  {{-- Header Kop --}}
  <div class="print-header">
    {{-- Logo Kab --}}
    @if(!empty($printSettings['logoKab']))
      <img src="{{ $printSettings['logoKab'] }}" class="print-logo" alt="Logo Kabupaten">
    @else
      <div class="print-logo-placeholder">LOGO<br>KAB</div>
    @endif

    <div class="print-header-text">
      <div class="district">{{ $printSettings['schoolDistrictHeader'] ?? 'PEMERINTAH DAERAH' }}</div>
      <div class="school-name">{{ $printSettings['schoolName'] }}</div>
      <div class="school-meta">
        NPSN: {{ $printSettings['schoolNpsn'] ?? '-' }}
        @if(!empty($printSettings['schoolAddress']))
          &nbsp;|&nbsp; {{ $printSettings['schoolAddress'] }}
        @endif
        @if(!empty($printSettings['schoolEmail']))
          &nbsp;|&nbsp; {{ $printSettings['schoolEmail'] }}
        @endif
      </div>
    </div>

    {{-- Logo Sekolah --}}
    @if(!empty($printSettings['logoSchool']))
      <img src="{{ $printSettings['logoSchool'] }}" class="print-logo" alt="Logo Sekolah">
    @else
      <div class="print-logo-placeholder">LOGO<br>SEKOLAH</div>
    @endif
  </div>

  {{-- Judul --}}
  <div class="print-title-box">
    <h2>REKAPITULASI KELENGKAPAN DOKUMEN SISWA</h2>
    <p>
      Status: {{ $status === 'Aktif' ? 'Siswa Aktif' : 'Alumni / Lulusan' }}
      &nbsp;|&nbsp; Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </p>
  </div>

  {{-- Summary --}}
  @php
    $totalSiswa   = $students->count();
    $totalLengkap = $students->filter(fn($s) => $s->doc_percent >= 100)->count();
    $totalBelum   = $totalSiswa - $totalLengkap;
    $avgPct       = $totalSiswa > 0 ? round($students->avg('doc_percent')) : 0;
  @endphp
  <div class="summary-row">
    <div class="summary-box blue">
      <div class="val">{{ $totalSiswa }}</div>
      <div class="lbl">Total Siswa</div>
    </div>
    <div class="summary-box green">
      <div class="val">{{ $totalLengkap }}</div>
      <div class="lbl">Dokumen Lengkap</div>
    </div>
    <div class="summary-box red">
      <div class="val">{{ $totalBelum }}</div>
      <div class="lbl">Belum Lengkap</div>
    </div>
    <div class="summary-box orange">
      <div class="val">{{ $avgPct }}%</div>
      <div class="lbl">Rata-rata</div>
    </div>
  </div>

  {{-- Table --}}
  <table>
    <thead>
      <tr>
        <th width="5%">No</th>
        <th width="15%">NISN</th>
        <th width="22%" style="text-align: left;">Nama Siswa</th>
        <th width="15%">Tgl. Lahir / Usia</th>
        <th width="12%">No. HP</th>
        <th width="11%">{{ $status == 'Lulus' ? 'Tahun Lulus' : 'Kelas' }}</th>
        <th width="20%">Status Dokumen</th>
      </tr>
    </thead>
    <tbody>
      @forelse($students as $student)
        <tr>
          <td style="text-align: center; color: #9ca3af;">{{ $loop->iteration }}</td>
          <td style="text-align: center;">{{ $student->nisn ?? '-' }}</td>
          <td><strong>{{ $student->nama }}</strong></td>
          <td style="text-align: center;">
            @if($student->birth_date)
              {{ \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') }}<br>
              <small style="color: #6b7280;">({{ \Carbon\Carbon::parse($student->birth_date)->age }} thn)</small>
            @else
              -
            @endif
          </td>
          <td style="text-align: center;">{{ $student->no_hp ?? '-' }}</td>
          <td style="text-align: center;">{{ $status == 'Lulus' ? ($student->tahun_lulus ?? '-') : ($student->kelas ?? '-') }}</td>
          <td style="text-align: center;">
            @php
              $docs_count = $student->documents->count();
              $approved_count = $student->documents->where('validation_status', 'approved')->count();
            @endphp
            @if($docs_count > 0)
              <span class="{{ $approved_count == $docs_count ? 'pct-full' : 'pct-low' }}" style="font-size: 11px;">
                {{ $approved_count }} Disetujui dari {{ $docs_count }}
              </span>
            @else
              <span class="pct-low" style="font-size: 11px;">Belum Ada Dokumen</span>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" style="text-align: center; padding: 20px; color: #9ca3af;">
            Tidak ada data siswa.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{-- Footer TTD --}}
  <div class="print-footer">
    <div class="print-date">
      Dicetak pada: {{ now()->translatedFormat('d F Y') }}
    </div>
    <div class="sign-box">
      <div class="sign-title">Mengetahui,<br>Kepala Sekolah</div>
      <div class="sign-space"></div>
      <div class="sign-name">{{ $printSettings['headmaster'] ?? '................................' }}</div>
      <div class="sign-nip">NIP. {{ $printSettings['nip'] ?? '................................' }}</div>
    </div>
  </div>

</body>
</html>
