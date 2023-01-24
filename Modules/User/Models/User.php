<?php

declare(strict_types=1);

namespace Modules\User\Models;

use App\Relationships\Traits\WhereHasForTenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Kalnoy\Nestedset\NodeTrait;
use Laravel\Sanctum\HasApiTokens;
use Modules\Brand\Models\Brand;
use Modules\Communication\Models\Call;
use Modules\Communication\Models\Comment;
use Modules\Communication\Models\CommunicationExtension;
use Modules\Communication\Models\CommunicationProvider;
use Modules\Communication\Models\DatabaseNotification;
use Modules\Communication\Models\Email;
use Modules\Customer\Models\Customer;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Geo\Models\Country;
use Modules\Language\Models\Language;
use Modules\Role\Models\Role;
use Modules\Role\Models\Traits\HasRoles;
use Modules\Token\Models\Token;
use Modules\User\Database\factories\UserFactory;
use Modules\User\Events\UserCreated;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

/**
 * Class User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property int|null $affiliate_id
 * @property bool $show_on_scoreboards
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
    use UsesLandlordConnection;
    use WhereHasForTenant;

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
        'password',
        'remember_token',
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
     * table
     *
     * @var string
     */
    protected $table = 'public.users';

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
        $user = request()?->user();
        if ($user instanceof self && $user->brands()->exists()) {
            $query = $query->whereHas('brands', function ($query) use ($user) {
                $query->whereIn('brands.id', $user->brands()->pluck('id')->toArray());
            });
        }

        return $query;
    }

    public function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = strtolower($value);
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
        return $this->belongsToManyTenant(Department::class, 'user_department');
    }

    /**
     * Desks relation.
     *
     * @return BelongsToMany
     */
    public function desks(): BelongsToMany
    {
        return $this->belongsToManyTenant(Desk::class, 'user_desk');
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
     * Countries relation.
     *
     * @return BelongsToMany
     */
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'user_country');
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
     * Presets relation.
     *
     * @return HasMany
     */
    public function presets(): HasMany
    {
        return $this->hasMany(Preset::class);
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

    /**
     * Communication provider.
     *
     * @return BelongsTo
     */
    public function communicationProvider(): BelongsTo
    {
        return $this->belongsTo(CommunicationProvider::class);
    }

    /**
     * Communication extensions.
     *
     * @return HasMany
     */
    public function communicationExtensions(): HasMany
    {
        return $this->hasMany(CommunicationExtension::class);
    }

    /**
     * Affiliate customers.
     *
     * @return HasMany
     */
    final public function affiliateCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'affiliate_user_id', 'id');
    }

    /**
     * Conversion customers.
     *
     * @return HasMany
     */
    final public function conversionCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'conversion_user_id', 'id');
    }

    /**
     * Retention customers.
     *
     * @return HasMany
     */
    final public function retentionCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'retention_user_id', 'id');
    }

    /**
     * Compliance customers.
     *
     * @return HasMany
     */
    final public function complianceCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'compliance_user_id', 'id');
    }

    /**
     * Support customers.
     *
     * @return HasMany
     */
    final public function supportCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'support_user_id', 'id');
    }

    /**
     * Conversion manager customers.
     *
     * @return HasMany
     */
    final public function conversionManageCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'conversion_manager_user_id', 'id');
    }

    /**
     * Retention manager customers.
     *
     * @return HasMany
     */
    final public function retentionManageCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'retention_manager_user_id', 'id');
    }

    /**
     * First conversion customers.
     *
     * @return HasMany
     */
    final public function firstConversionCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'first_conversion_user_id', 'id');
    }

    /**
     * First retention customers.
     *
     * @return HasMany
     */
    final public function firstRetentionCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'first_retention_user_id', 'id');
    }

    /**
     * Get the entity's notifications.
     *
     * @return MorphMany
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->latest();
    }

    /**
     * Affiliate Token
     *
     * @return hasMany
     */
    public function affiliateToken(): hasMany
    {
        return $this->hasMany(Token::class);
    }

    /**
     * Users emails
     *
     * @return MorphMany
     */
    public function emails(): MorphMany
    {
        return $this->morphMany(Email::class, 'emailable')->latest();
    }

    /**
     * User send emails
     *
     * @return MorphMany
     */
    public function sendEmails(): MorphMany
    {
        return $this->morphMany(Email::class, 'sendemailable')->latest();
    }

    /**
     * User calls
     *
     * @return MorphMany
     */
    public function calls(): MorphMany
    {
        return $this->morphMany(Call::class, 'callable')->latest();
    }

    /**
     * User send calls
     *
     * @return MorphMany
     */
    public function sendCalls(): MorphMany
    {
        return $this->morphMany(Call::class, 'sendcallable')->latest();
    }
}
