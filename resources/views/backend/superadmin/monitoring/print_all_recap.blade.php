<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rekap Kelengkapan Dokumen - Semua Lembaga</title>
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
      flex-direction: column;
      align-items: center;
      gap: 4px;
      margin-bottom: 20px;
    }

    .print-title-box {
      text-align: center;
      background: #1e293b;
      color: #fff;
      padding: 10px 24px;
      border-radius: 6px;
      margin-bottom: 16px;
      width: 100%;
    }
    .print-title-box h2 { font-size: 15px; font-weight: 700; letter-spacing: 1px; margin-bottom: 4px; }
    .print-title-box h3 { font-size: 13px; font-weight: 600; color: #e2e8f0; margin-bottom: 6px; }
    .print-title-box p  { font-size: 11px; opacity: 0.9; margin-top: 2px; }

    /* ---- Table ---- */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 4px;
    }
    thead th {
      background: #1e293b;
      color: #fff;
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      padding: 8px 6px;
      text-align: center;
      border: 1px solid #374151;
      vertical-align: middle;
    }

    tbody td {
      border: 1px solid #e5e7eb;
      padding: 6px 6px;
      font-size: 11px;
      vertical-align: middle;
    }
    tbody tr:nth-child(even) { background: #f8fafc; }

    .success {
      color: #16a34a;
      font-weight: 700;
    }

    .danger {
      color: #dc2626;
      font-weight: 700;
    }

    .page-break {
      page-break-after: always;
    }

    /* ---- Footer ---- */
    .print-footer {
      margin-top: 20px;
      text-align: right;
      font-size: 10px;
      color: #555;
    }

    /* ---- Print controls ---- */
    .no-print {
      margin: 16px 0;
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
      .print-title-box { background: #1e293b !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
      thead th { background: #1e293b !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
      tbody tr:nth-child(even) { background: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
  </style>
</head>

<body onload="window.print()">
  <div class="no-print">
    <button class="btn-p primary" onclick="window.print()">
      🖨️ Cetak / Simpan PDF
    </button>
    <button class="btn-p secondary" onclick="window.close()">
      Tutup
    </button>
  </div>

  @foreach($recapData as $item)
  <div class="{{ !$loop->last ? 'page-break' : '' }}">
    
    <div class="print-title-box">
      <h2>REKAPITULASI KELENGKAPAN DOKUMEN</h2>
      <h3>{{ $item['tenant']->nama_sekolah }}</h3>
      <p>
        Status Siswa: {{ ucfirst($status) }}
        @if($age_filter)
          | Filter Usia: {{ $age_filter == 'under_25' ? 'Di Bawah 25 Tahun' : '25 Tahun ke Atas' }}
        @endif
      </p>
    </div>

    <table>
      <thead>
        <tr>
          <th width="5%">No</th>
          <th width="15%">NISN</th>
          <th width="22%" style="text-align: left;">Nama Siswa</th>
          <th width="15%">Tgl. Lahir / Usia</th>
          <th width="12%">No. HP</th>
          <th width="11%">{{ $status == 'lulus' ? 'Tahun Lulus' : 'Kelas' }}</th>
          <th width="20%">Status Dokumen</th>
        </tr>
      </thead>
      <tbody>
        @forelse($item['data'] as $student)
          <tr>
            <td style="text-align: center;">{{ $loop->iteration }}</td>
            <td style="text-align: center;">{{ $student->nisn ?? '-' }}</td>
            <td><strong>{{ $student->nama }}</strong></td>
            <td style="text-align: center;">
              @if($student->birth_date)
                {{ \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') }}<br>
                <small class="text-muted">({{ \Carbon\Carbon::parse($student->birth_date)->age }} thn)</small>
              @else
                -
              @endif
            </td>
            <td style="text-align: center;">{{ $student->no_hp ?? '-' }}</td>
            <td style="text-align: center;">{{ $status == 'lulus' ? ($student->tahun_lulus ?? '-') : ($student->kelas ?? '-') }}</td>
            <td style="text-align: center;">
              @php
                $docs_count = $student->documents->count();
                $approved_count = $student->documents->where('validation_status', 'approved')->count();
              @endphp
              @if($docs_count > 0)
                <span class="{{ $approved_count == $docs_count ? 'success' : 'danger' }}">
                  {{ $approved_count }} Disetujui dari {{ $docs_count }}
                </span>
              @else
                <span class="danger">Belum Ada Dokumen</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 15px;">Tidak ada data siswa ditemukan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div class="print-footer">
      <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
  </div>
  @endforeach

</body>
</html>
