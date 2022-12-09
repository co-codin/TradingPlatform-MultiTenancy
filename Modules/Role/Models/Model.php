<?php

namespace Modules\Role\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Modules\Role\Database\factories\ModelFactory;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class Model extends BaseModel
{
    use HasFactory;
    use UsesLandlordConnection;

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
        return ModelFactory::new();
    }
}
