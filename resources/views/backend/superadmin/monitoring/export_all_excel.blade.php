<table>
    <thead>
        <tr>
            <th colspan="7" style="text-align: center; font-size: 16px; font-weight: bold;">
                REKAP DATA {{ strtoupper($category == 'graduates' ? 'LULUSAN' : 'SISWA AKTIF') }} SEMUA LEMBAGA
            </th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center;">
                Tanggal Unduh: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
            </th>
        </tr>
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
        @foreach($tenants as $index => $tenant)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $tenant->npsn }}</td>
                <td>{{ $tenant->nama_sekolah }}</td>
                <td>{{ $tenant->email }}</td>
                <td>{{ $tenant->stats_total }}</td>
                <td>{{ $tenant->stats_l }}</td>
                <td>{{ $tenant->stats_p }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
