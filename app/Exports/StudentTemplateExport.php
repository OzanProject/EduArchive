<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentTemplateExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
  public function collection()
  {
    // Example Data
    return collect([
      [
        'Budi Santoso',
        'L', // Gender
        '0054321', // NISN
        '3501012005050001', // NIK
        'VII-A',
        '2010-05-20',
        'Jakarta',
        'Jl. Merdeka No. 1',
        'Sutrisno'
      ]
    ]);
  }

  public function headings(): array
  {
    return [
      'nama_lengkap',
      'jenis_kelamin', // L/P
      'nisn',
      'nik',
      'kelas',
      'tanggal_lahir',
      'tempat_lahir',
      'alamat',
      'nama_orang_tua',
    ];
  }

  public function styles(Worksheet $sheet)
  {
    return [
      // Style the first row as bold text
      1 => ['font' => ['bold' => true]],
    ];
  }
}
