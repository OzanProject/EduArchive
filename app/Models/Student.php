<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Student extends Model
{
  use HasFactory, BelongsToTenant;

  protected $connection = 'mysql';

  protected $fillable = [
    'nama',
    'gender', // Added gender
    'no_hp', // Added phone number
    'classroom_id',
    'kelas', // Legacy field, might be removed later or kept for sync
    'status_kelulusan',
    'foto_profil',
    'tahun_lulus',
    // Enterprise Fields
    'nisn',
    'nik',
    'birth_place',
    'birth_date',
    'address',
    'parent_name',
    'year_in',
    'year_out',
    'no_seri_ijazah',
  ];

  protected $casts = [
    'birth_date' => 'date',
  ];

  public function documents()
  {
    return $this->hasMany(Document::class);
  }

  public function graduation()
  {
    return $this->hasOne(Graduation::class);
  }

  public function classroom()
  {
    return $this->belongsTo(Classroom::class);
  }
}
