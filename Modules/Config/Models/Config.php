<?php

namespace Modules\Config\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Config\Database\factories\ConfigFactory;

class Config extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function configType()
    {
        return $this->belongsTo(ConfigType::class);
    }

    protected static function newFactory()
    {
        return ConfigFactory::new();
    }
}
