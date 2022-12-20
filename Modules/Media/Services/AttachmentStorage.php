<?php

declare(strict_types=1);

namespace Modules\Media\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Media\Models\Attachment;

final class AttachmentStorage
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var Collection
     */
    protected Collection $attachments;

    /**
     * Update.
     *
     * @param  Model  $model
     * @param  array  $attachments
     * @return Collection
     */
    public function update(Model $model, array $attachments = []): Collection
    {
        DB::beginTransaction();

        $this->deleteNonExistentAttachments()
            ->createNewAttachments()
            ->updateExistingAttachments();

        DB::commit();

        return $this->attachments;
    }

    /**
     * Delete non existent attachemnts.
     *
     * @return AttachmentStorage
     */
    protected function deleteNonExistentAttachments(): AttachmentStorage
    {
        $ids = $this->attachments->pluck('id')->filter()->unique();

        $this->model->attachments()
            ->when($ids->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $ids))
            ->delete();

        return $this;
    }

    /**
     * Create new attachment.
     *
     * @return AttachmentStorage
     */
    protected function createNewAttachments(): AttachmentStorage
    {
        $newAttachments = $this->attachments->filter(fn ($item) => ! Arr::exists($item, 'id'));

        $this->model->attachments()->createMany($newAttachments);

        return $this;
    }

    /**
     * Update existing attachments.
     *
     * @return AttachmentStorage
     */
    protected function updateExistingAttachments(): AttachmentStorage
    {
        $this->attachments
            ->filter(fn ($image) => Arr::exists($image, 'id'))
            ->each(function ($image) {
                Attachment::query()->find($image['id'])?->update($image);
            });

        return $this;
    }

    /**
     * Set model.
     *
     * @param  Model  $model
     * @return AttachmentStorage
     */
    public function setModel(Model $model): AttachmentStorage
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Set attachments.
     *
     * @param  Collection  $attachments
     * @return AttachmentStorage
     */
    public function setAttachments(Collection $attachments): AttachmentStorage
    {
        $this->attachments = $attachments;

        return $this;
    }
}
