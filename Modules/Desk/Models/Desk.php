<?php

namespace Modules\Desk\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;
use Modules\Desk\Database\factories\DeskFactory;

class Desk extends Model
{
    use HasFactory, SoftDeletes, NodeTrait;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return DeskFactory::new();
    }
}
