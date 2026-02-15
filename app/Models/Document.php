<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
  use HasFactory;

  protected $connection = 'tenant';

  protected $fillable = [
    'student_id',
    'document_type',
    'file_path',
    'file_size',
    'mime_type',
    'uploaded_by',
    'keterangan',
    'verified_at',
  ];

  protected $casts = [
    'verified_at' => 'datetime',
  ];

  public function student()
  {
    return $this->belongsTo(Student::class);
  }

  public function uploader()
  {
    return $this->belongsTo(User::class, 'uploaded_by');
  }
}
