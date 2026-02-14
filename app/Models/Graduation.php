<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graduation extends Model
{
  use HasFactory;

  protected $fillable = [
    'student_id',
    'graduation_year',
  ];

  public function student()
  {
    return $this->belongsTo(Student::class);
  }
}
