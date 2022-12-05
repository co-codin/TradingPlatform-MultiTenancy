<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Media\Database\factories\ImageFactory;

class Image extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function imageable()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return ImageFactory::new();
    }
}
