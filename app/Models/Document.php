<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Document extends Model
{
  use HasFactory, BelongsToTenant;

  protected $connection = 'mysql';

  protected $fillable = [
    'student_id',
    'document_type',
    'file_path',
    'file_size',
    'mime_type',
    'uploaded_by',
    'keterangan',
    'verified_at',
    'validation_status',
    'validation_notes',
    'validated_by',
    'validated_at',
  ];

  protected $casts = [
    'verified_at' => 'datetime',
    'validated_at' => 'datetime',
  ];

  // Accessor for backward compatibility
  public function getJenisDokumenAttribute()
  {
    return $this->document_type;
  }

  public function student()
  {
    return $this->belongsTo(Student::class);
  }

  public function uploader()
  {
    return $this->belongsTo(User::class, 'uploaded_by');
  }

  public function validator()
  {
    // Validator is from central database (Super Admin)
    return $this->belongsTo(\App\Models\User::class, 'validated_by')->withoutGlobalScopes();
  }

  // Scopes
  public function scopePending($query)
  {
    return $query->where('validation_status', 'pending');
  }

  public function scopeApproved($query)
  {
    return $query->where('validation_status', 'approved');
  }

  public function scopeRejected($query)
  {
    return $query->where('validation_status', 'rejected');
  }
}
