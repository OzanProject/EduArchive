<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageUsage extends Model
{
  use HasFactory;

  protected $fillable = [
    'used_space',
    'last_calculated',
  ];

  protected $casts = [
    'last_calculated' => 'datetime',
  ];
}
