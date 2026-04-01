<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class IntegrityPact extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'title',
        'file_path',
        'status',
        'status_notes',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
