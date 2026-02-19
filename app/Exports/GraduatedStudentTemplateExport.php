<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GraduatedStudentTemplateExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
  public function collection()
  {
    return collect([
      [
        'Budi Santoso',
        '0054321', // NISN
        '3501012005050001', // NIK
        'VII-A', // Kelas Terakhir
        '2010-05-20',
        'Jakarta',
        'Jl. Merdeka No. 1',
        'Sutrisno',
        '2024' // Tahun Lulus
      ]
    ]);
  }

  public function headings(): array
  {
    return [
      'nama_lengkap',
      'nisn',
      'nik',
      'kelas_terakhir',
      'tanggal_lahir',
      'tempat_lahir',
      'alamat',
      'nama_orang_tua',
      'no_hp',
      'tahun_lulus',
    ];
  }

  public function styles(Worksheet $sheet)
  {
    return [
      1 => ['font' => ['bold' => true]],
    ];
  }
}
