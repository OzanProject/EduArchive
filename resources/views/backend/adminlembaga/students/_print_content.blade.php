<div class="container {{ $isBulk && !$isLast ? 'page-break' : '' }}">
  <!-- Kop Surat -->
  <div class="kop-surat">
    <table class="kop-table">
      <tr>
        <td class="logo-cell">
          @if($printSettings['logoKab'])
            <img src="{{ $printSettings['logoKab'] }}" class="logo-img" alt="Logo Kab">
          @endif
        </td>
        <td class="kop-text">
          <h2>{{ $printSettings['schoolDistrictHeader'] }}</h2>
          <h1>{{ $printSettings['schoolName'] }}</h1>
          <p>Alamat : {{ $printSettings['schoolAddress'] }}</p>
          <p>E-mail : {{ $printSettings['schoolEmail'] }} | NPSN : {{ $printSettings['schoolNpsn'] }}</p>
        </td>
        <td class="logo-cell">
          @if($printSettings['logoSchool'])
            <img src="{{ $printSettings['logoSchool'] }}" class="logo-img" alt="Logo Sekolah">
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
      <div style="width: 110px; height: 147px; border: 1px solid #000; display: inline-flex; align-items: center; justify-content: center;">
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
      <td>NISN</td>
      <td>:</td>
      <td>{{ $student->nisn ? $student->nisn : '-' }}</td>
    </tr>
    <tr>
      <td>NIK</td>
      <td>:</td>
      <td>{{ $student->nik ? $student->nik : '-' }}</td>
    </tr>
    @if($student->no_seri_ijazah)
      <tr>
        <td>No. Seri Ijazah</td>
        <td>:</td>
        <td>{{ $student->no_seri_ijazah }}</td>
      </tr>
    @endif
    <tr>
      <td>Jenis Kelamin</td>
      <td>:</td>
      <td>{{ $student->gender == 'L' ? 'Laki-laki' : ($student->gender == 'P' ? 'Perempuan' : '-') }}</td>
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
      <p>{{ $printSettings['schoolCity'] }}, {{ date('d F Y') }}</p>
      <p>Kepala Sekolah,</p>

      <div class="signature-container">
        @if($printSettings['stamp'])
          <img src="{{ $printSettings['stamp'] }}" class="stamp-img" alt="Stempel">
        @endif
        @if($printSettings['signature'])
          <img src="{{ $printSettings['signature'] }}" class="signature-img" alt="Tanda Tangan">
        @endif
      </div>

      <p><strong><u>{{ $printSettings['headmaster'] }}</u></strong><br>NIP. {{ $printSettings['nip'] }}</p>
    </div>
    <div style="clear: both;"></div>
  </div>
</div>
