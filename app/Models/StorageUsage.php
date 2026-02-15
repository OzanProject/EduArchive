<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class StorageUsage extends Model
{
  use HasFactory, BelongsToTenant;

  protected $connection = 'mysql';

  protected $fillable = [
    'used_space',
    'last_calculated',
  ];

  protected $casts = [
    'last_calculated' => 'datetime',
  ];
}
