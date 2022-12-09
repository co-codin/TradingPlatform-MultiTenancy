<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Kalnoy\Nestedset\NodeTrait;
use Laravel\Sanctum\HasApiTokens;
use Modules\Brand\Models\Brand;
use Modules\Communication\Models\Comment;
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
 * @property int|null $affiliate_id
 * @property boolean $show_on_scoreboards
 * @property-read Role $role
 * @property-read Role[]|Collection $roles
 * @property-read Brand[]|Collection $brands
 * @property-read Department[]|Collection $departments
 * @property-read Desk[]|Collection $desks
 * @property-read Language[]|Collection $languages
 * @property-read DisplayOption[]|Collection $displayOptions
 * @property-read User $affiliate
 *
 * @method static self create(array $attributes)
 */
final class User extends Authenticatable
{
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
        'show_on_scoreboards' => 'boolean',
        'banned_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
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

    /**
     * Scope for querying users by permissions access.
     *
     * @param $query
     * @return mixed
     *
     * @throws Exception
     */
    public function scopeByPermissionsAccess($query): Builder
    {
        return match (true) {
            request()->user()?->isAdmin() => $query,
            request()->user()?->brands()->exists() => $query->whereHas('brands', function ($query) {
                $query->whereIn(
                    'brands.id',
                    request()->user()
                        ->brands()
                        ->pluck('id')
                        ->toArray(),
                );
            }),
            default => abort(403, __('Cant access get users.')),
        };
    }

    public function toArray()
    {
        if (auth()->check()) {
            $this->makeVisible($this->hidden);
        }

        return parent::toArray();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
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

    /**
     * Affiliate relation.
     *
     * @return BelongsTo
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affiliate_id', 'id');
    }

    public function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function setUsernameAttribute(string $value): void
    {
        $this->attributes['username'] = strtolower($value);
    }
}
