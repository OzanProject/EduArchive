<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Classroom extends Model
{
    use BelongsToTenant;
    protected $connection = 'mysql';
    protected $guarded = ['id'];

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

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'wali_kelas_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
