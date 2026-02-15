<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class SchoolDocument extends Model
{
    use HasFactory, BelongsToTenant;

    protected $connection = 'mysql';

    protected $fillable = [
        'title',
        'category',
        'file_path',
        'file_size',
        'mime_type',
        'description',
        'uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
