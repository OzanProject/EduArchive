<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Classroom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
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
      'nisn' => $row['nisn'] ?? null,
      'nik' => $row['nik'] ?? null,
      'classroom_id' => $classroom ? $classroom->id : null,
      'kelas' => $classroom ? $classroom->nama_kelas : ($row['kelas'] ?? null),
      'birth_place' => $row['tempat_lahir'] ?? null,
      'birth_date' => $birthDate,
      'parent_name' => $row['nama_orang_tua'] ?? null,
      'address' => $row['alamat'] ?? null,
      'status_kelulusan' => $this->status,
      'tahun_lulus' => $row['tahun_lulus'] ?? null,
    ]);
  }

  public function rules(): array
  {
    return [
      'nama_lengkap' => 'required',
      'nisn' => 'nullable|unique:students,nisn',
      'nik' => 'nullable|unique:students,nik',
    ];
  }
}
