<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolDocument extends Model
{
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
