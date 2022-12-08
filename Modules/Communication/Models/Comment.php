<?php

namespace Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Communication\Database\factories\CommentFactory;
use Modules\Media\Models\Image;
use Modules\User\Models\User;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')
            ->orderBy('position');
    }
    protected static function newFactory()
    {
        return CommentFactory::new();
    }
}