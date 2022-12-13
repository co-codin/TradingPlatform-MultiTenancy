<?php

declare(strict_types=1);

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Brand\Models\Brand;
use Modules\Customer\Database\factories\CustomerFactory;
use Modules\Customer\Events\CustomerSaving;
use Modules\Customer\Models\Traits\CustomerRelations;
use Modules\Role\Models\Traits\HasRoles;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Multitenancy\Models\Tenant;

/**
 * Class Customer
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property int $gender
 * @property string $email
 * @property string $phone
 * @property int|null $affiliate_user_id
 * @property int|null $conversion_user_id
 * @property int|null $retention_user_id
 * @property int|null $compliance_user_id
 * @property int|null $support_user_id
 * @property int|null $conversion_manager_user_id
 * @property int|null $retention_manager_user_id
 * @property int|null $first_conversion_user_id
 * @property int|null $first_retention_user_id
 * @property int|null $conversion_sale_status_id
 * @property int|null $retention_sale_status_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
final class Customer extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;
    use CustomerRelations;
    use HasRoles;
    use HasApiTokens;
    use UsesTenantConnection;

    /**
     * @var array
     */
    public const WORKER_COLUMNS_FOR_EMAILING = [
        'affiliate_user_id',
        'conversion_user_id',
        'retention_user_id',
        'compliance_user_id',
        'support_user_id',
        'conversion_manager_user_id',
        'retention_manager_user_id',
        'first_conversion_user_id',
        'first_retention_user_id',
    ];

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'password',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'last_online' => 'datetime',
        'first_autologin_time' => 'datetime',
        'first_login_time' => 'datetime',
        'first_deposit_date' => 'datetime',
        'last_approved_deposit_date' => 'datetime',
        'last_pending_deposit_date' => 'datetime',
        'last_pending_withdrawal_date' => 'datetime',
        'last_communication_date' => 'datetime',
        'balance' => 'decimal:2',
        'balance_usd' => 'decimal:2',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dispatchesEvents = [
        'saving' => CustomerSaving::class,
    ];

    /**
     * {@inheritdoc}
     */
    protected $rememberTokenName = false;

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

    /**
     * Get brand.
     *
     * @return Brand|null
     */
    public function getBrand(): ?Brand
    {
        return app(Tenant::class)->current();
    }
}
