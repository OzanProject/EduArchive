<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeacherTemplateExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
  public function collection()
  {
    // Example Data
    return collect([
      [
        '198501012010011001',
        'Dr. Budi Santoso, M.Pd.',
        'budi@sekolah.sch.id',
        '081234567890',
        'Jl. Pendidikan No. 10',
        'Guru Mapel',
        'L',
        'PNS'
      ]
    ]);
  }

  public function headings(): array
  {
    return [
      'nip',
      'nama_lengkap',
      'email',
      'no_hp',
      'alamat',
      'jabatan',
      'jenis_kelamin',
      'status_kepegawaian',
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
