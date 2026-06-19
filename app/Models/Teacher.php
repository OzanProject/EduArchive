<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Teacher extends Model
{
    use HasFactory, BelongsToTenant;

    protected $connection = 'mysql';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    protected static function booted()
    {
        $clearCache = function () {
            if (function_exists('tenant') && tenant('id')) {
                \Illuminate\Support\Facades\Cache::forget('tenant_public_stats_' . tenant('id'));
            }
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'wali_kelas_id');
    }
}
