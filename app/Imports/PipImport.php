<?php

namespace App\Imports;

use App\Models\PipData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PipImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new PipData([
            'nisn' => $row['nisn'] ?? null,
            'nama_siswa' => $row['nama_siswa'] ?? $row['nama'] ?? 'Tanpa Nama',
            'tahun_usulan' => $row['tahun_usulan'] ?? date('Y'),
            'tahap' => $row['tahap'] ?? null,
            'pesan_lembaga' => $row['pesan_lembaga'] ?? $row['keterangan'] ?? null,
            'status' => 'usulan_sekolah',
        ]);
    }
}
