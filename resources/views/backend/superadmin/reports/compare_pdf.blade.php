<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Perbandingan Lembaga</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      color: #333;
    }
    .header {
      text-align: center;
      margin-bottom: 20px;
      border-bottom: 2px solid #555;
      padding-bottom: 10px;
    }
    .header h1 {
      margin: 0;
      font-size: 18px;
      text-transform: uppercase;
    }
    .header p {
      margin: 5px 0 0;
      font-size: 12px;
      color: #555;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      border: 1px solid #999;
      padding: 6px;
      text-align: left;
    }
    th {
      background-color: #f4f4f4;
      font-weight: bold;
      text-align: center;
    }
    .text-center {
      text-align: center;
    }
    .font-weight-bold {
      font-weight: bold;
    }
    .bg-light {
      background-color: #f9f9f9;
    }
    .footer {
      margin-top: 30px;
      text-align: right;
      font-size: 11px;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>REKAPITULASI STATISTIK SELURUH LEMBAGA</h1>
    <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
  </div>

  <table>
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Nama Sekolah</th>
            <th rowspan="2">NPSN</th>
            <th rowspan="2">Jenjang</th>
            <th colspan="2">Siswa</th>
            <th colspan="{{ count($docTypes) }}">Upload Dokumen</th>
            <th rowspan="2">Total Guru</th>
            <th rowspan="2">Proposal Sarpras</th>
            <th rowspan="2">Log Belajar</th>
            <th rowspan="2">Total PIP</th>
        </tr>
        <tr>
            <th>Aktif</th>
            <th>Lulusan</th>
            @foreach($docTypes as $type)
              <th>{{ $type }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
      @forelse($stats as $index => $row)
        <tr>
          <td style="text-align: center;">{{ $index + 1 }}</td>
          <td>{{ $row['nama_sekolah'] }}</td>
          <td style="text-align: center;">{{ $row['npsn'] }}</td>
          <td style="text-align: center;">{{ $row['jenjang'] }}</td>
          <td style="text-align: center;">{{ $row['active_students'] }}</td>
          <td style="text-align: center;">{{ $row['graduated_students'] }}</td>
          @foreach($docTypes as $type)
            <td style="text-align: center;">{{ $row['documents'][$type] ?? 0 }}</td>
          @endforeach
          <td style="text-align: center;">{{ $row['total_teachers'] }}</td>
          <td style="text-align: center;">{{ $row['pending_infrastructure'] }}</td>
          <td style="text-align: center;">{{ $row['total_learning'] }}</td>
          <td style="text-align: center; font-weight: bold;">{{ $row['total_pip'] ?? 0 }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="{{ 9 + count($docTypes) }}" class="text-center">Belum ada data sekolah.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="footer">
    <p>Laporan dihasilkan secara otomatis oleh Sistem EduArchive.</p>
  </div>

</body>
</html>
