<?php

declare(strict_types=1);

namespace App\Services\Storage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class StorageService
{
    /**
     * Save file in storage.
     *
     * @param string $path
     * @param UploadedFile|string $file
     * @param array $options
     * @param string|null $name
     * @return bool|string
     */
    public function save(string $path, UploadedFile|string $file, array $options = [], ?string $name = null): bool|string
    {
        return $name
            ? Storage::putFileAs($path, $file, $name, $options)
            : Storage::putFile($path, $file, $options);
    }

    /**
     * Save file in tmp storage.
     *
     * @param string $path
     * @param UploadedFile|string $file
     * @param array $options
     * @param string|null $name
     * @return bool|string
     */
    public function saveTmp(string $path, UploadedFile|string $file, array $options = [], ?string $name = null): bool|string
    {
        return $this->save(storage_path("tmp/{$path}"), $file, $options, $name);
    }
}
