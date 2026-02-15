<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Graduation extends Model
{
  use HasFactory, BelongsToTenant;

  protected $connection = 'mysql';

  protected $fillable = [
    'student_id',
    'graduation_year',
  ];

  public function student()
  {
    return $this->belongsTo(Student::class);
  }
}
