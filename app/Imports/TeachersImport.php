<?php

namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class TeachersImport implements ToModel, WithHeadingRow, WithValidation
{
  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function model(array $row)
  {
    return new Teacher([
      'nip' => $row['nip'] ?? null,
      'nama_lengkap' => $row['nama_lengkap'],
      'email' => $row['email'] ?? null,
      'no_hp' => $row['no_hp'] ?? null,
      'alamat' => $row['alamat'] ?? null,
      'jabatan' => $row['jabatan'] ?? 'Guru',
      'jenis_kelamin' => $row['jenis_kelamin'] ?? 'L', // Default L
      'status_kepegawaian' => $this->mapStatus($row['status_kepegawaian'] ?? null),
      'is_active' => true,
    ]);
  }

  private function mapStatus($status)
  {
    $validStatuses = ['PNS', 'PPPK', 'GTY', 'GTT', 'Honor Daerah', 'Lainnya'];

    if (in_array($status, $validStatuses)) {
      return $status;
    }

    // Mapping common variations
    return match (strtoupper($status)) {
      'HONOR', 'HONORER' => 'Honor Daerah',
      'ASN' => 'PNS',
      'P3K' => 'PPPK',
      default => 'Lainnya',
    };
  }

  public function rules(): array
  {
    return [
      'nama_lengkap' => 'required',
      'email' => 'nullable|email|unique:teachers,email',
      'nip' => 'nullable|unique:teachers,nip',
    ];
  }
}
