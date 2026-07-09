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
        'Budi Santoso', // nama_lengkap
        'L', // jenis_kelamin
        '0054321', // nisn
        '3501012005050001', // nik
        'VII-A', // kelas
        '2010-05-20', // tanggal_lahir
        'Jakarta', // tempat_lahir
        'Jl. Merdeka No. 1', // alamat
        'Sutrisno', // nama_orang_tua
        '08123456789', // no_hp
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
      'no_hp',
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
