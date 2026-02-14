<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $connection = 'mysql'; // Ensure it always uses central DB

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'is_required',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_required' => 'boolean',
    ];
}
