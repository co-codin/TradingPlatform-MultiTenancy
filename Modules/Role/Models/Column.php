<?php

namespace Modules\Role\Models;

use App\Models\Traits\ForTenant;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use ForTenant;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
