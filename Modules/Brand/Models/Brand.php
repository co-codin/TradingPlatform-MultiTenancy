<?php

namespace Modules\Brand\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Modules\Brand\Database\factories\BrandFactory;
use Modules\User\Models\User;

/**
 * @property int $id;
 * @property string $title;
 * @property string $logo_url;
 * @property bool $is_active;
 * @property string $slug;
 *
 */
class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return BrandFactory::new();
    }
}
