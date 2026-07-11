<?php

namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Str;

class TeachersImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function model(array $row)
  {
    return new Teacher([
      'nip' => $this->parseNip($row['nip'] ?? null),
      'nama_lengkap' => $row['nama_lengkap'],
      'email' => $row['email'] ?? null,
      'no_hp' => $row['no_hp'] ?? null,
      'alamat' => $row['alamat'] ?? null,
      'jabatan' => $row['jabatan'] ?? 'Guru',
      'jenis_kelamin' => $this->parseGender($row['jenis_kelamin'] ?? null),
      'status_kepegawaian' => $this->mapStatus($row['status_kepegawaian'] ?? null),
      'is_active' => true,
    ]);
  }

  private function parseNip($nip)
  {
    if (empty($nip)) return null;
    $nip = trim($nip);
    return $nip === '-' ? null : $nip;
  }

  private function parseGender($jk)
  {
    if (empty($jk)) return 'L';
    $jk = strtoupper(trim($jk));

    return match ($jk) {
      'LAKI-LAKI', 'LAKI', 'MALE', 'L' => 'L',
      'PEREMPUAN', 'WANITA', 'FEMALE', 'P' => 'P',
      default => 'L',
    };
  }

  private function mapStatus($status)
  {
    if (empty($status)) return 'Lainnya';
    $status = trim($status);
    $validStatuses = ['PNS', 'PPPK', 'GTY', 'GTT', 'Honor Daerah', 'Lainnya'];

    if (in_array($status, $validStatuses)) {
      return $status;
    }

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
      'nama_lengkap' => 'required|string|max:255',
      'email' => 'nullable|email|unique:teachers,email',
      'nip' => 'nullable|string|unique:teachers,nip',
    ];
  }

  public function customValidationMessages()
  {
    return [
      'nama_lengkap.required' => 'Nama lengkap wajib diisi pada file Excel.',
      'email.email' => 'Format email tidak valid.',
      'email.unique' => 'Email :input sudah terdaftar di sistem.',
      'nip.unique' => 'NIP :input sudah terdaftar di sistem.',
    ];
  }

  public function chunkSize(): int
  {
    return 100;
  }
}
