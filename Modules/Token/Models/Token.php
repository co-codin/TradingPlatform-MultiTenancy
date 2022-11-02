<?php

namespace Modules\Token\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Token\Database\factories\TokenFactory;

class Token extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return TokenFactory::new();
    }
}
