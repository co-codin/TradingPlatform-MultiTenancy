<?php

namespace Modules\Role\Models;

use App\Models\Traits\ForTenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Role\Database\factories\ActionFactory;

class Action extends Model
{
    use ForTenant, HasFactory;

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
