<?php

declare(strict_types=1);

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Communication\Database\factories\CommentFactory;
use Modules\Media\Models\Image;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UsesTenantConnection;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return CommentFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')
            ->orderBy('position');
    }
}
