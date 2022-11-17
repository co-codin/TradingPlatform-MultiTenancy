<?php

namespace Modules\Config\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Config\Database\factories\ConfigTypeFactory;

class ConfigType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return ConfigTypeFactory::new();
    }
}
