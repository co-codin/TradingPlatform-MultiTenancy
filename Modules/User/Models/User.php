<?php

declare(strict_types=1);

namespace Modules\User\Models;

use App\Models\Traits\ForTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Kalnoy\Nestedset\NodeTrait;
use Laravel\Sanctum\HasApiTokens;
use Modules\Brand\Models\Brand;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Language\Models\Language;
use Modules\Role\Models\Role;
use Modules\Role\Models\Traits\HasRoles;
use Modules\User\Database\factories\UserFactory;
use Modules\User\Events\UserCreated;

/**
 * Class User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property-read Role $role
 * @property-read Role[]|Collection $roles
 * @property-read Brand[]|Collection $brands
 * @property-read Department[]|Collection $departments
 * @property-read Desk[]|Collection $desks
 * @property-read Language[]|Collection $languages
 * @property-read DisplayOption[]|Collection $displayOptions
 *
 * @method static self create(array $attributes)
 */
final class User extends Authenticatable
{
    use ForTenant;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use NodeTrait;
    use Notifiable;
    use SoftDeletes;

    /**
     * @var string
     */
    public const DEFAULT_AUTH_GUARD = 'api';

    /**
     * {@inheritdoc}
     */
    protected $guarded = [
        'id',
    ];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'banned_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dispatchesEvents = [
        'created' => UserCreated::class,
    ];

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function toArray()
    {
        if (auth()->check()) {
            $this->makeVisible($this->hidden);
        }

        return parent::toArray();
    }

    /**
     * Brands relation.
     *
     * @return BelongsToMany
     */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'user_brand');
    }

    /**
     * Departments relation.
     *
     * @return BelongsToMany
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'user_department');
    }

    /**
     * Desks relation.
     *
     * @return BelongsToMany
     */
    public function desks(): BelongsToMany
    {
        return $this->belongsToMany(Desk::class, 'user_desk');
    }

    /**
     * Languages relation.
     *
     * @return BelongsToMany
     */
    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'user_language');
    }

    /**
     * Display options relation.
     *
     * @return HasMany
     */
    public function displayOptions(): HasMany
    {
        return $this->hasMany(DisplayOption::class);
    }

    public function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = strtolower($value);
    }
}
