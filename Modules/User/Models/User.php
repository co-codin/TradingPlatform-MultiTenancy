<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Modules\Brand\Models\Brand;
use Modules\Desk\Models\Desk;
use Modules\Language\Models\Language;
use Modules\Role\Models\Role;
use Modules\User\Database\factories\UserFactory;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * @package Modules\User\Models
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property-read Role $role
 * @property-read Role[]|Collection $roles
 * @method static self create(array $attributes)
 */
final class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens, HasFactory, SoftDeletes;

    /**
     * @var string
     */
    public const DEFAULT_AUTH_GUARD = 'sanctum';

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'is_active',
        'target',
        'parent_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'user_brand');
    }

    public function desks()
    {
        return $this->belongsToMany(Desk::class, 'user_desk');
    }

    public function languags()
    {
        return $this->belongsToMany(Language::class, 'user_language');
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
