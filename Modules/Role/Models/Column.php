<?php

namespace Modules\Role\Models;

use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    protected $guarded = ['id'];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
