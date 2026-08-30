<!DOCTYPE html>
<html>
<head>
    <title>Rekap Data {{ $category == 'graduates' ? 'Lulusan' : 'Siswa Aktif' }} Semua Lembaga</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 0; padding: 0; }
        .header p { margin: 5px 0; }
    </style>
</head>
<body>

    <div class="header">
        <h3>REKAP DATA {{ strtoupper($category == 'graduates' ? 'LULUSAN' : 'SISWA AKTIF') }} SEMUA LEMBAGA</h3>
        <p>Tanggal Unduh: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        @if($age_filter)
            <p>Filter Usia: {{ $age_filter == 'under_25' ? 'Kurang dari 25 Tahun' : 'Lebih dari sama dengan 25 Tahun' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NPSN</th>
                <th>Nama Sekolah</th>
                <th>Email</th>
                <th>Total {{ $category == 'graduates' ? 'Lulusan' : 'Siswa' }}</th>
                <th>Laki-laki</th>
                <th>Perempuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tenants as $index => $tenant)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $tenant->npsn }}</td>
                    <td>{{ $tenant->nama_sekolah }}</td>
                    <td>{{ $tenant->email }}</td>
                    <td class="text-center">{{ $tenant->stats_total }}</td>
                    <td class="text-center">{{ $tenant->stats_l }}</td>
                    <td class="text-center">{{ $tenant->stats_p }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data lembaga.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
