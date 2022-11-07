<?php

namespace Modules\Worker\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Brand\Models\Brand;
use Modules\Desk\Models\Desk;
use Modules\Token\Models\Token;
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

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function desks()
    {
        return $this->belongsToMany(Desk::class, 'worker_desk', 'worker_id', 'desk_id');
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    protected static function newFactory()
    {
        return WorkerFactory::new();
    }
}
