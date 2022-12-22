<?php

declare(strict_types=1);

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Media\Database\factories\AttachmentFactory;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

final class Attachment extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    /**
     * {@inheritdoc}
     */
    protected $guarded = ['id'];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * @return MorphTo
     */
    public function attachmentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * {@inheritDoc}
     */
    protected static function newFactory(): AttachmentFactory
    {
        return AttachmentFactory::new();
    }
}
