<?php

declare(strict_types=1);

namespace Modules\Media\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Media\Models\Attachment;

trait HasAttachment
{
    /**
     * Attachments relation.
     *
     * @return MorphMany
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachmentable')
            ->orderBy('position');
    }
}
