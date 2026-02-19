<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class LearningActivity extends Model
{
  use HasFactory, BelongsToTenant;

  protected $fillable = [
    'tenant_id',
    'activity_name',
    'method',
    'day',
    'time_start',
    'time_end',
    'description',
    'activity_image',
    'status',
    'status_notes',
  ];

  /**
   * Get the tenant that owns the learning activity.
   */
  public function tenant()
  {
    return $this->belongsTo(Tenant::class);
  }
}
