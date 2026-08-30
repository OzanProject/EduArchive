<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'from_tenant_id',
        'to_tenant_id',
        'moved_by_user_id',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fromTenant()
    {
        return $this->belongsTo(Tenant::class, 'from_tenant_id');
    }

    public function toTenant()
    {
        return $this->belongsTo(Tenant::class, 'to_tenant_id');
    }
}
