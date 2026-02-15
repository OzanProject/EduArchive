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

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'wali_kelas_id');
    }
}
