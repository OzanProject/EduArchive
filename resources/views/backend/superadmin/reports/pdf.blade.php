<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Laporan Statistik - {{ $tenant->nama_sekolah }}</title>
  <style>
    body {
      font-family: 'Helvetica', sans-serif;
      font-size: 12px;
      color: #333;
      line-height: 1.5;
    }

    .header {
      text-align: center;
      border-bottom: 2px solid #444;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .header h1 {
      margin: 0;
      font-size: 18px;
      text-transform: uppercase;
    }

    .header p {
      margin: 5px 0 0;
      font-size: 11px;
      color: #666;
    }

    .section-title {
      background-color: #f4f4f4;
      padding: 5px 10px;
      font-weight: bold;
      border-left: 4px solid #007bff;
      margin-top: 20px;
      margin-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }

    table th,
    table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    table th {
      background-color: #f9f9f9;
      width: 40%;
    }

    .footer {
      margin-top: 30px;
      text-align: right;
      font-size: 10px;
      color: #999;
    }

    .page-break {
      page-break-after: always;
    }

    .stats-grid {
      width: 100%;
    }

    .stats-col {
      width: 50%;
      vertical-align: top;
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>Laporan Statistik Sekolah</h1>
    <h1>{{ $tenant->nama_sekolah }}</h1>
    <p>NPSN: {{ $tenant->npsn }} | Jenjang: {{ $tenant->jenjang }} | Alamat: {{ $tenant->alamat }}</p>
    <p>Tanggal Cetak: {{ date('d F Y H:i') }}</p>
  </div>

  <div class="section-title">1. Ringkasan Data Siswa</div>
  <table class="stats-grid">
    <tr>
      <td class="stats-col">
        <table>
          <tr>
            <th>Status Siswa</th>
            <th>Jumlah</th>
          </tr>
          <tr>
            <td>Aktif</td>
            <td>{{ $stats['students']['active'] }}</td>
          </tr>
          <tr>
            <td>Lulusan</td>
            <td>{{ $stats['students']['graduated'] }}</td>
          </tr>
          <tr>
            <td>Lainnya</td>
            <td>{{ $stats['students']['others'] }}</td>
          </tr>
          <tr style="font-weight: bold;">
            <td>Total Siswa</td>
            <td>{{ $stats['students']['total'] }}</td>
          </tr>
        </table>
      </td>
      <td class="stats-col">
        <table>
          <tr>
            <th>Jenis Kelamin</th>
            <th>Jumlah</th>
          </tr>
          <tr>
            <td>Laki-laki</td>
            <td>{{ $stats['gender']['L'] }}</td>
          </tr>
          <tr>
            <td>Perempuan</td>
            <td>{{ $stats['gender']['P'] }}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <div class="section-title">2. Demografi & Kelas</div>
  <table class="stats-grid">
    <tr>
      <td class="stats-col">
        <table>
          <tr>
            <th>Kelompok Usia</th>
            <th>Jumlah</th>
          </tr>
          @foreach($stats['age_stats'] as $label => $val)
            <tr>
              <td>{{ $label }} Tahun</td>
              <td>{{ $val }}</td>
            </tr>
          @endforeach
        </table>
      </td>
      <td class="stats-col">
        <table>
          <tr>
            <th>Data Akademik</th>
            <th>Jumlah</th>
          </tr>
          <tr>
            <td>Total Kelas (Rombel)</td>
            <td>{{ $stats['classrooms'] }}</td>
          </tr>
          <tr>
            <td>Total Guru</td>
            <td>{{ $stats['teachers']['total'] }}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <div class="section-title">3. Statistik Arsip & Dokumen</div>
  <table>
    <tr>
      <th>Kategori Dokumen</th>
      <th>Jumlah File</th>
    </tr>
    <tr>
      <td>Arsip Dokumen Sekolah</td>
      <td>{{ $stats['school_documents'] }}</td>
    </tr>
    <tr>
      <td>Dokumen Persyaratan Siswa (Total)</td>
      <td>{{ $stats['documents'] }}</td>
    </tr>
  </table>

  <div class="section-title">4. Monitoring Kegiatan Pembelajaran</div>
  <table>
    <tr>
      <th>Status Kegiatan</th>
      <th>Jumlah</th>
    </tr>
    <tr>
      <td>Total Kegiatan Tercatat</td>
      <td>{{ $stats['learning_activities']['total'] }}</td>
    </tr>
    <tr style="color: #666;">
      <td>- Menunggu (Pending)</td>
      <td>{{ $stats['learning_activities']['pending'] }}</td>
    </tr>
    <tr style="color: #28a745;">
      <td>- Disetujui (Approved)</td>
      <td>{{ $stats['learning_activities']['approved'] }}</td>
    </tr>
    <tr style="color: #dc3545;">
      <td>- Ditolak (Rejected)</td>
      <td>{{ $stats['learning_activities']['rejected'] }}</td>
    </tr>
  </table>

  <div class="section-title">6. Detail Usia Siswa Aktif</div>
  <table>
    <thead>
      <tr>
        <th style="width: 5%;">No</th>
        <th style="width: 40%;">Nama Siswa</th>
        <th style="width: 20%;">Kelas</th>
        <th style="width: 10%;">L/P</th>
        <th style="width: 15%;">Tgl Lahir</th>
        <th style="width: 10%;">Usia</th>
      </tr>
    </thead>
    <tbody>
      @forelse($stats['student_details'] as $index => $item)
        <tr>
          <td style="text-align: center;">{{ $index + 1 }}</td>
          <td>{{ $item['nama'] }}</td>
          <td>{{ $item['kelas'] }}</td>
          <td style="text-align: center;">{{ $item['gender'] }}</td>
          <td>{{ $item['birth_date'] }}</td>
          <td style="text-align: center;">{{ $item['age'] ?? '-' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="text-align: center;">Tidak ada data siswa aktif.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="footer">
    Dicetak secara otomatis melalui Sistem EduArchive Monitoring pada {{ date('d/m/Y H:i:s') }}
  </div>
</body>

</html>