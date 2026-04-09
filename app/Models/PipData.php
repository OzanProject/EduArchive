<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class PipData extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'pip_data';

    protected $fillable = [
        'nisn',
        'nama_siswa',
        'tahun_usulan',
        'tahap',
        'nominal',
        'status',
        'pesan_lembaga',
        'pesan_dinas',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
