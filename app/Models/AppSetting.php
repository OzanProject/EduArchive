<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class AppSetting extends Model
{
  use HasFactory, BelongsToTenant;

  protected $connection = 'mysql';

  protected $guarded = ['id'];

  /**
   * Get setting value by key
   *
   * @param string $key
   * @param mixed $default
   * @return mixed
   */
  public static function getSetting($key, $default = null)
  {
    // Cache settings for performance
    $tenantId = tenant('id') ?? 'global';
    return Cache::rememberForever("tenant_{$tenantId}_app_setting_{$key}", function () use ($key, $default) {
      $setting = self::where('key', $key)->first();
      return $setting ? $setting->value : $default;
    });
  }
}
