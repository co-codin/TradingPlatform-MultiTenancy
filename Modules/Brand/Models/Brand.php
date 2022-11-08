<?php

namespace Modules\Brand\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Modules\Brand\Database\factories\BrandFactory;
use Modules\User\Models\User;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setConnection($name)
    {
        $this->connection = Session::get('brand') ?? $name;

        return $this;
    }

    protected static function newFactory()
    {
        return BrandFactory::new();
    }
}
