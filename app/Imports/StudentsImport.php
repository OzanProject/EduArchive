<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Classroom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, \Maatwebsite\Excel\Concerns\WithBatchInserts, \Maatwebsite\Excel\Concerns\WithChunkReading
{
  protected $status;

  public function __construct($status)
  {
    $this->status = $status;
  }

  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function model(array $row)
  {
    // Find Classroom by Name
    $classroom = null;
    if (!empty($row['kelas'])) {
      $classroom = Classroom::where('nama_kelas', $row['kelas'])->first();
    }

    // Parse Date of Birth
    $birthDate = null;
    if (!empty($row['tanggal_lahir'])) {
      try {
        // Try parsing YYYY-MM-DD or DD-MM-YYYY
        $birthDate = Carbon::parse($row['tanggal_lahir']);
      } catch (\Exception $e) {
        // Ignore parsing error, leave null
      }
    }

    return new Student([
      'nama' => $row['nama_lengkap'],
      'gender' => $this->parseGender($row['jenis_kelamin'] ?? null), // Parse Gender
      'nisn' => $this->parseNullableString($row['nisn'] ?? null),
      'nik' => $this->parseNullableString($row['nik'] ?? null),
      'classroom_id' => $classroom ? $classroom->id : null,
      'kelas' => $classroom ? $classroom->nama_kelas : ($row['kelas'] ?? null),
      'birth_place' => $row['tempat_lahir'] ?? null,
      'birth_date' => $birthDate,
      'parent_name' => $row['nama_orang_tua'] ?? null,
      'address' => $row['alamat'] ?? null,
      'no_hp' => $row['no_hp'] ?? $row['nomor_hp'] ?? null,
      'status_kelulusan' => $this->status,
      'tahun_lulus' => $row['tahun_lulus'] ?? null,
    ]);
  }

  private function parseNullableString($val)
  {
    if (empty($val)) return null;
    $val = trim($val);
    return $val === '-' ? null : $val;
  }

  private function parseGender($value)
  {
    if (!$value)
      return null;
    $val = strtoupper(trim($value));
    if (in_array($val, ['L', 'LAKI-LAKI', 'LAKI LAKI', 'PRIA', 'MALE']))
      return 'L';
    if (in_array($val, ['P', 'PEREMPUAN', 'WOMAN', 'WANITA', 'FEMALE']))
      return 'P';
    return null;
  }

  public function rules(): array
  {
    return [
      'nama_lengkap' => 'required|string|max:255',
      'jenis_kelamin' => 'required', // Gender is required
      'nisn' => [
        'nullable',
        'string',
        Rule::unique('students', 'nisn')->where('status_kelulusan', 'Aktif')
      ],
      'nik' => [
        'nullable',
        'string',
        Rule::unique('students', 'nik')->where('status_kelulusan', 'Aktif')
      ],
    ];
  }

  public function customValidationMessages()
  {
    return [
      'nama_lengkap.required' => 'Nama lengkap wajib diisi pada file Excel.',
      'jenis_kelamin.required' => 'Jenis kelamin wajib diisi pada file Excel.',
      'nisn.unique' => 'NISN :input sudah terdaftar untuk siswa aktif.',
      'nik.unique' => 'NIK :input sudah terdaftar untuk siswa aktif.',
    ];
  }

  public function batchSize(): int
  {
    return 100;
  }

  public function chunkSize(): int
  {
    return 100;
  }
}
