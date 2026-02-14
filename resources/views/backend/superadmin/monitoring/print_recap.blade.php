<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rekap Kelengkapan Dokumen - {{ $tenant->nama_sekolah }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    .header h2 {
      margin: 0;
    }

    .header p {
      margin: 5px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 6px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    .success {
      color: green;
      font-weight: bold;
    }

    .danger {
      color: red;
      font-weight: bold;
    }

    @media print {
      .no-print {
        display: none;
      }
    }
  </style>
</head>

<body onload="window.print()">
  <div class="no-print" style="margin-bottom: 10px;">
    <button onclick="window.print()">Cetak / Simpan PDF</button>
    <button onclick="window.close()">Tutup</button>
  </div>

  <div class="header">
    <h2>REKAPITULASI KELENGKAPAN DOKUMEN</h2>
    <h3>{{ $tenant->nama_sekolah }}</h3>
    <p>Status: {{ ucfirst($status) }} {{ $year ? '- Tahun Lulus: ' . $year : '' }}</p>
  </div>

  <table>
    <thead>
      <tr>
        <th width="5%">No</th>
        <th width="15%">NISN</th>
        <th width="30%">Nama Siswa</th>
        <th width="15%">{{ $status == 'lulus' ? 'Tahun Lulus' : 'Kelas' }}</th>
        <th width="35%">Status Dokumen</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $student)
        <tr>
          <td style="text-align: center;">{{ $loop->iteration }}</td>
          <td>{{ $student->nisn }}</td>
          <td>{{ $student->nama }}</td>
          <td>{{ $status == 'lulus' ? $student->tahun_lulus : $student->kelas }}</td>
          <td>
            @if($student->documents->count() > 0)
              <span class="success">LENGKAP ({{ $student->documents->count() }} Dokumen)</span>
            @else
              <span class="danger">BELUM ADA DOKUMEN</span>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" style="text-align: center;">Tidak ada data siswa.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top: 20px; text-align: right;">
    <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
  </div>
</body>

</html>