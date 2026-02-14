<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TenantUser extends Authenticatable
{
  use HasFactory, Notifiable;

  protected $table = 'users';

  protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'last_login',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'last_login' => 'datetime',
  ];
}
