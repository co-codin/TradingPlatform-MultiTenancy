<?php

namespace Modules\Brand\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Brand\Database\factories\BrandFactory;
use Modules\Worker\Models\Worker;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    protected static function newFactory()
    {
        return BrandFactory::new();
    }
}
