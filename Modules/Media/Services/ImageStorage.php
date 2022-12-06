<?php

namespace Modules\Media\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Media\Models\Image;

class ImageStorage
{
    protected Model $model;

    protected Collection $images;

    public function update(Model $model, array $images = [])
    {
        DB::beginTransaction();

        $this->model = $model;
        $this->images = collect($images);

        $this
            ->deleteNonExistentImages()
            ->createNewImages()
            ->updateExistingImages();

        DB::commit();
    }

    protected function deleteNonExistentImages()
    {
        $ids = $this->images->pluck('id')->filter()->unique();

        $this->model->images()
            ->when($ids->isNotEmpty(), fn($query) => $query->whereNotIn('id', $ids))
            ->delete();

        return $this;
    }

    protected function createNewImages()
    {
        $newImages = $this->images->filter(fn($item) => !Arr::exists($item, 'id'));

        $this->model->images()->createMany($newImages);

        return $this;
    }

    protected function updateExistingImages()
    {
        $this->images
            ->filter(fn($image) => Arr::exists($image, 'id'))
            ->each(function($image) {
                $model = Image::query()->find($image['id']);
                if($model) {
                    $model->update($image);
                }
            });

        return $this;
    }
}
