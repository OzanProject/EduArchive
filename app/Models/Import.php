<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
  use HasFactory;

  protected $fillable = [
    'file_name',
    'total_rows',
    'success_rows',
    'failed_rows',
    'imported_by',
  ];

  public function failures()
  {
    return $this->hasMany(ImportFailure::class);
  }

  public function importer()
  {
    return $this->belongsTo(TenantUser::class, 'imported_by');
  }
}
