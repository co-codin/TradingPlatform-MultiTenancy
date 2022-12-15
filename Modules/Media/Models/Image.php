<?php

declare(strict_types=1);

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Media\Database\factories\ImageFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Image extends Model
{
    use HasFactory;
    use UsesTenantConnection;

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
