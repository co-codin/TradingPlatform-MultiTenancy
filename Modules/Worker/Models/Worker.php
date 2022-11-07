<?php
declare(strict_types=1);

namespace Modules\Worker\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Modules\Desk\Models\Desk;
use Modules\Role\Models\Role;
use Modules\Token\Models\Token;
use Modules\Worker\Database\factories\WorkerFactory;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class Worker
 * @package Modules\User\Models
 * @property int $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property-read Role $role
 * @property-read Role[]|Collection $roles
 * @property-read Desk[]|Collection $desks
 * @method static self create(array $attributes)
 * @method Token createToken(string $name)
 */
class Worker extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'username', 'first_name', 'last_name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function desks()
    {
        return $this->belongsToMany(
            Desk::class,
            'worker_desk',
            'worker_id',
            'desk_id'
        );
    }

    protected static function newFactory()
    {
        return WorkerFactory::new();
    }
}
