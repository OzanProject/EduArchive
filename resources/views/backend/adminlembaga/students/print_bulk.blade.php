@php
  use App\Models\AppSetting;
  $logoKab = AppSetting::getSetting('logo_kabupaten');
  $logoSchool = AppSetting::getSetting('school_logo');
  $schoolName = AppSetting::getSetting('school_name', tenant('id'));
  $schoolAddress = AppSetting::getSetting('school_address', 'Alamat Sekolah Belum Diisi');
  $schoolEmail = AppSetting::getSetting('school_email', '-');
  $schoolNpsn = AppSetting::getSetting('school_npsn', '-');
  $schoolDistrictHeader = AppSetting::getSetting('school_district_header', 'PEMERINTAH KABUPATEN CIANJUR');

  $headmaster = AppSetting::getSetting('school_headmaster_name', '..........................');
  $nip = AppSetting::getSetting('school_headmaster_nip', '..........................');
  $signature = AppSetting::getSetting('school_signature');
  $stamp = AppSetting::getSetting('school_stamp');
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Data Siswa Masal</title>
  <style>
    body {
      font-family: 'Times New Roman', Times, serif;
      font-size: 12pt;
      line-height: 1.3;
      color: #000;
    }

    .container {
      width: 100%;
      max-width: 800px;
      margin: 0 auto;
      padding: 10px 20px;
    }

    /* Kop Surat */
    .kop-surat {
      width: 100%;
      border-bottom: 4px double #000;
      padding-bottom: 5px;
      margin-bottom: 15px;
    }

    .kop-table {
      width: 100%;
      border: none;
      margin-bottom: 0px;
    }

    .kop-table td {
      vertical-align: middle;
      text-align: center;
    }

    .logo-cell {
      width: 15%;
    }

    .logo-img {
      width: 75px;
      height: auto;
    }

    .kop-text {
      width: 70%;
      padding: 0 5px;
    }

    .kop-text h2 {
      margin: 0;
      font-size: 14pt;
      font-weight: bold;
      text-transform: uppercase;
      line-height: 1.2;
    }

    .kop-text h1 {
      margin: 2px 0;
      font-size: 18pt;
      font-weight: bold;
      text-transform: uppercase;
      line-height: 1.2;
    }

    .kop-text p {
      margin: 0;
      font-size: 10pt;
      font-style: italic;
      line-height: 1.2;
    }

    .header-title {
      text-align: center;
      margin: 10px 0;
      text-decoration: underline;
      font-weight: bold;
      font-size: 14pt;
    }

    .photo-section {
      text-align: center;
      margin-bottom: 10px;
    }

    .photo-section img {
      width: 110px;
      height: 147px;
      object-fit: cover;
      border: 1px solid #777;
      padding: 3px;
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 5px;
    }

    .data-table td {
      padding: 4px 5px;
      vertical-align: top;
      font-size: 12pt;
    }

    .data-table td:first-child {
      width: 200px;
    }

    .data-table td:nth-child(2) {
      width: 20px;
      text-align: center;
    }

    .footer {
      margin-top: 30px;
      width: 100%;
      font-size: 12pt;
    }

    .ttd-kanan {
      width: 40%;
      float: right;
      position: relative;
      text-align: center;
    }

    .signature-container {
      position: relative;
      height: 90px;
      width: 220px;
      margin: 5px auto;
    }

    .signature-img {
      height: 80px;
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      z-index: 2;
    }

    .stamp-img {
      height: 75px;
      position: absolute;
      bottom: 5px;
      left: 0;
      z-index: 1;
      opacity: 0.8;
      transform: rotate(-5deg);
    }

    @media print {
      @page {
        margin: 1cm;
        size: A4 portrait;
      }

      body {
        -webkit-print-color-adjust: exact;
      }

      .page-break {
        page-break-after: always;
      }
    }
  </style>
</head>

<body onload="window.print()">

  @foreach($students as $index => $student)
    <div class="container {{ !$loop->last ? 'page-break' : '' }}">
      <!-- Kop Surat -->
      <div class="kop-surat">
        <table class="kop-table">
          <tr>
            <td class="logo-cell">
              @if($logoKab)
                <img src="{{ $logoKab }}" class="logo-img" alt="Logo Kab">
              @endif
            </td>
            <td class="kop-text">
              <h2>{{ $schoolDistrictHeader }}</h2>
              <h1>{{ $schoolName }}</h1>
              <p>Alamat : {{ $schoolAddress }}</p>
              <p>E-mail : {{ $schoolEmail }} | NPSN : {{ $schoolNpsn }}</p>
            </td>
            <td class="logo-cell">
              @if($logoSchool)
                <img src="{{ $logoSchool }}" class="logo-img" alt="Logo Sekolah">
              @endif
            </td>
          </tr>
        </table>
      </div>

      <div class="header-title">BIODATA PESERTA DIDIK</div>

      <div class="photo-section">
        @if($student->foto_profil)
          <img src="{{ tenant_asset($student->foto_profil) }}" alt="Foto Profil">
        @else
          <div
            style="width: 110px; height: 147px; border: 1px solid #000; display: inline-flex; align-items: center; justify-content: center;">
            No Photo
          </div>
        @endif
      </div>

      <table class="data-table">
        <tr>
          <td>Nama Lengkap</td>
          <td>:</td>
          <td><strong>{{ $student->nama }}</strong></td>
        </tr>
        <tr>
          <td>NISN / NIK</td>
          <td>:</td>
          <td>{{ $student->nisn ? $student->nisn : '-' }} / {{ $student->nik ? $student->nik : '-' }}</td>
        </tr>
        <tr>
          <td>Tempat, Tanggal Lahir</td>
          <td>:</td>
          <td>{{ $student->birth_place ?? '-' }},
            {{ $student->birth_date ? $student->birth_date->format('d F Y') : '-' }}
          </td>
        </tr>
        <tr>
          <td>Kelas Saat Ini</td>
          <td>:</td>
          <td>{{ $student->classroom ? $student->classroom->nama_kelas : '-' }}</td>
        </tr>
        <tr>
          <td>Tahun Masuk</td>
          <td>:</td>
          <td>{{ $student->year_in ?? '-' }}</td>
        </tr>
        <tr>
          <td>Nama Orang Tua/Wali</td>
          <td>:</td>
          <td>{{ $student->parent_name ?? '-' }}</td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td>:</td>
          <td>{{ $student->address ?? '-' }}</td>
        </tr>
        <tr>
          <td>Status Siswa</td>
          <td>:</td>
          <td><strong>{{ $student->status_kelulusan }}</strong></td>
        </tr>
      </table>

      <div class="footer">
        <div class="ttd-kanan">
          <p>Cianjur, {{ date('d F Y') }}</p>
          <p>Kepala Sekolah,</p>

          <div class="signature-container">
            @if($stamp)
              <img src="{{ $stamp }}" class="stamp-img" alt="Stempel">
            @endif
            @if($signature)
              <img src="{{ $signature }}" class="signature-img" alt="Tanda Tangan">
            @endif
          </div>

          <p><strong><u>{{ $headmaster }}</u></strong><br>NIP. {{ $nip }}</p>
        </div>
        <div style="clear: both;"></div>
      </div>
    </div>
  @endforeach

</body>

</html>