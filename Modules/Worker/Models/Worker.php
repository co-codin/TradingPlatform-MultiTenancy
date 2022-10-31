<?php

namespace Modules\Worker\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Worker\Database\factories\WorkerFactory;
use Spatie\Permission\Traits\HasRoles;

class Worker extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens, HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return WorkerFactory::new();
    }
}
