<table>
    <tr>
        <th colspan="7" style="text-align: center; font-size: 14px;"><b>REKAPITULASI DATA SISWA {{ strtoupper($tenant->nama_sekolah) }}</b></th>
    </tr>
    <tr>
        <td colspan="2"><b>Status Kelulusan</b></td>
        <td colspan="5">: {{ $status == 'lulus' ? 'Lulus / Alumni' : 'Siswa Aktif' }}</td>
    </tr>
    @if($status == 'lulus' && $year)
    <tr>
        <td colspan="2"><b>Tahun Lulus</b></td>
        <td colspan="5">: {{ $year }}</td>
    </tr>
    @endif
    <tr>
        <td colspan="2"><b>Filter Usia</b></td>
        <td colspan="5">: 
            @if($age_filter == 'under_25')
                Usia &lt; 25 Tahun
            @elseif($age_filter == 'over_25')
                Usia &ge; 25 Tahun
            @else
                Semua Usia
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2"><b>Total Siswa</b></td>
        <td colspan="5">: {{ $totalSiswa }} Siswa</td>
    </tr>
    <tr>
        <td colspan="2"><b>Sudah Verifikasi Dokumen</b></td>
        <td colspan="5">: {{ $sudahVerifikasi }} Siswa</td>
    </tr>
    <tr>
        <td colspan="2"><b>Belum Verifikasi Dokumen</b></td>
        <td colspan="5">: {{ $belumVerifikasi }} Siswa</td>
    </tr>
    <tr>
        <th colspan="{{ 7 + count($docTypes) }}"></th>
    </tr>
    <tr>
        <th style="background-color: #f2f2f2; border: 1px solid #000; text-align: center;"><b>No</b></th>
        <th style="background-color: #f2f2f2; border: 1px solid #000; text-align: center;"><b>NISN</b></th>
        <th style="background-color: #f2f2f2; border: 1px solid #000; text-align: center;"><b>Nama Siswa</b></th>
        <th style="background-color: #f2f2f2; border: 1px solid #000; text-align: center;"><b>Tgl. Lahir</b></th>
        <th style="background-color: #f2f2f2; border: 1px solid #000; text-align: center;"><b>Usia</b></th>
        <th style="background-color: #f2f2f2; border: 1px solid #000; text-align: center;"><b>Kelas</b></th>
        <th style="background-color: #f2f2f2; border: 1px solid #000; text-align: center;"><b>Status Verifikasi</b></th>
        @foreach($docTypes as $type)
        <th style="background-color: #f2f2f2; border: 1px solid #000; text-align: center;"><b>{{ $type->name }}</b></th>
        @endforeach
    </tr>
    @foreach($data as $index => $student)
    <tr>
        <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
        <td style="border: 1px solid #000;">{{ $student->nisn }}</td>
        <td style="border: 1px solid #000;">{{ $student->nama }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') : '-' }}</td>
        <td style="border: 1px solid #000; text-align: center;">{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->age . ' tahun' : '-' }}</td>
        <td style="border: 1px solid #000;">{{ $student->kelas }}</td>
        <td style="border: 1px solid #000; text-align: center; color: {{ $student->is_verified ? 'green' : 'red' }};">
            <b>{{ $student->is_verified ? 'Sudah Verifikasi' : 'Belum Verifikasi' }}</b>
        </td>
        @foreach($docTypes as $type)
        @php
            $hasDoc = $student->documents->contains('document_type', $type->name);
        @endphp
        <td style="border: 1px solid #000; text-align: center; color: {{ $hasDoc ? 'green' : 'red' }};">
            {{ $hasDoc ? 'Sudah Upload' : 'Belum Upload' }}
        </td>
        @endforeach
    </tr>
    @endforeach
</table>
