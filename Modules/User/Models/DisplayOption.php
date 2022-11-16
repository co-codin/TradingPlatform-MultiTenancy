<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Database\factories\UserDisplayOptionFactory;

class DisplayOption extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'columns' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return UserDisplayOptionFactory::new();
    }
}
