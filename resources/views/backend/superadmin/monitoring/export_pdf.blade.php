<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Data Siswa - {{ $tenant->nama_sekolah }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 0; padding: 0; font-size: 18px; }
        .header h4 { margin: 5px 0 0 0; padding: 0; font-size: 16px; }
        .info-table { margin-bottom: 20px; width: 100%; }
        .info-table td { padding: 3px; font-size: 12px; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 6px; text-align: left; }
        .data-table th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center !important; }
        .text-success { color: green; font-weight: bold; }
        .text-danger { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h3>REKAPITULASI DATA SISWA</h3>
        <h4>{{ strtoupper($tenant->nama_sekolah) }}</h4>
    </div>

    <table class="info-table">
        <tr>
            <td width="25%"><b>Status Kelulusan</b></td>
            <td width="2%">:</td>
            <td>{{ $status == 'lulus' ? 'Lulus / Alumni' : 'Siswa Aktif' }}</td>
        </tr>
        @if($status == 'lulus' && $year)
        <tr>
            <td><b>Tahun Lulus</b></td>
            <td>:</td>
            <td>{{ $year }}</td>
        </tr>
        @endif
        <tr>
            <td><b>Filter Usia</b></td>
            <td>:</td>
            <td>
                @if($age_filter == 'under_25') Usia &lt; 25 Tahun
                @elseif($age_filter == 'over_25') Usia &ge; 25 Tahun
                @else Semua Usia
                @endif
            </td>
        </tr>
        <tr>
            <td><b>Total Siswa</b></td>
            <td>:</td>
            <td>{{ $totalSiswa }} Siswa</td>
        </tr>
        <tr>
            <td><b>Sudah Verifikasi Dokumen</b></td>
            <td>:</td>
            <td>{{ $sudahVerifikasi }} Siswa</td>
        </tr>
        <tr>
            <td><b>Belum Verifikasi Dokumen</b></td>
            <td>:</td>
            <td>{{ $belumVerifikasi }} Siswa</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Tgl. Lahir</th>
                <th>Usia</th>
                <th>Kelas</th>
                <th>Status Verifikasi</th>
                @foreach($docTypes as $type)
                <th>{{ $type->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $student)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $student->nisn }}</td>
                <td>{{ $student->nama }}</td>
                <td class="text-center">{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '-' }}</td>
                <td class="text-center">{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->age . ' thn' : '-' }}</td>
                <td class="text-center">{{ $student->kelas }}</td>
                <td class="text-center">
                    @if($student->is_verified)
                        <span class="text-success">Sudah Verifikasi</span>
                    @else
                        <span class="text-danger">Belum Verifikasi</span>
                    @endif
                </td>
                @foreach($docTypes as $type)
                @php
                    $hasDoc = $student->documents->contains('document_type', $type->name);
                @endphp
                <td class="text-center">
                    @if($hasDoc)
                        <span class="text-success">✔</span>
                    @else
                        <span class="text-danger">✘</span>
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
