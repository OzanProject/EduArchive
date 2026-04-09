<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PipTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'nisn',
            'nama_siswa',
            'tahun_usulan',
            'tahap',
            'pesan_lembaga'
        ];
    }

    public function array(): array
    {
        return [
            [
                '1234567890',
                'Contoh Siswa',
                date('Y'),
                '1',
                'Siswa pindahan'
            ]
        ];
    }
}
