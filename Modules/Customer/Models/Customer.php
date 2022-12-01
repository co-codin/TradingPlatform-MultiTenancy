<?php

namespace Modules\Customer\Models;

use App\Models\Traits\ForTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Customer\Database\factories\CustomerFactory;
use Modules\Customer\Models\Traits\CustomerRelations;
use Modules\Geo\Models\Country;
use Modules\Role\Models\Traits\HasRoles;

/**
 * Class Customer
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property int $gender
 * @property string $email
 * @property string $phone
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Customer extends Authenticatable
{
    use HasFactory, SoftDeletes, CustomerRelations, HasRoles, HasApiTokens, ForTenant;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
    ];

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

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    protected static function newFactory()
    {
        return CustomerFactory::new();
    }
}
