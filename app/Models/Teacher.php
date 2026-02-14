<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $guarded = ['id'];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'wali_kelas_id');
    }
}
