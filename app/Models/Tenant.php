<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;

/**
 * @property string $id
 * @property string|null $npsn
 * @property string|null $nama_sekolah
 * @property string|null $jenjang
 * @property string|null $alamat
 * @property int $status_aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array<array-key, mixed>|null $data
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Stancl\Tenancy\Database\Models\Domain> $domains
 * @property-read int|null $domains_count
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> all($columns = ['*'])
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereJenjang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereNamaSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereNpsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereStatusAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tenant extends BaseTenant
{
  use HasDomains;

  public static function getCustomColumns(): array
  {
    return [
      'id',
      'npsn',
      'nama_sekolah',
      'jenjang',
      'alamat',
      'logo',
      'status_aktif',
      'subscription_plan',
      'storage_limit',
    ];
  }

  public function storageUsage()
  {
    return $this->hasOne(StorageUsage::class);
  }

  public function getUsedStorage()
  {
    return $this->storageUsage?->used_space ?? 0;
  }

  public function checkStorageLimit(int $newFileSize)
  {
    if (is_null($this->storage_limit)) {
      return true; // Unlimited
    }

    $currentUsage = $this->getUsedStorage();
    if (($currentUsage + $newFileSize) > $this->storage_limit) { // storage_limit is in Bytes
      return false;
    }

    return true;
  }

  public function updateStorageUsage(int $bytes, bool $increment = true)
  {
    $usage = $this->storageUsage()->firstOrCreate([], ['used_space' => 0]);

    if ($increment) {
      $usage->increment('used_space', $bytes);
    } else {
      $usage->decrement('used_space', $bytes);
    }

    $usage->touch('last_calculated');
  }
}
