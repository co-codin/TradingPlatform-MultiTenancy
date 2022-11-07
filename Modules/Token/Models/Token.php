<?php
declare(strict_types=1);

namespace Modules\Token\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Token\Database\factories\TokenFactory;
use Modules\Worker\Models\Worker;

class Token extends PersonalAccessToken
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    protected static function newFactory()
    {
        return TokenFactory::new();
    }
}
