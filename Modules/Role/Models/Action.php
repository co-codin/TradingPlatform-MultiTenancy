<?php

namespace Modules\Role\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $guarded = ['id'];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
