<?php

namespace Modules\Role\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Role\Database\factories\ActionFactory;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class Action extends Model
{
    use HasFactory;
    use UsesLandlordConnection;

    /**
     * @var array
     */
    public const NAMES = [
        'viewAny' => 'viewAny',
        'view' => 'view',
        'create' => 'create',
        'edit' => 'edit',
        'delete' => 'delete',
        'impersonate' => 'impersonate',
        'export' => 'export',
        'import' => 'import',
        'ban' => 'ban',
    ];

    protected $guarded = ['id'];

    public $timestamps = false;

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): Factory
    {
        return ActionFactory::new();
    }
}
