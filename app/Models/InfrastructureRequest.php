<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class InfrastructureRequest extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'type',
        'title',
        'description',
        'proposed_budget',
        'status',
        'media_path',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
