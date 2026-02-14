<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportFailure extends Model
{
  use HasFactory;

  protected $fillable = [
    'import_id',
    'row_number',
    'error_message',
  ];

  public function import()
  {
    return $this->belongsTo(Import::class);
  }
}
