<?php

namespace Modules\Media\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploader
{
    protected string $dir;

    protected string $disk = "s3";

    public function __construct()
    {
        $this->dir = $this->defaultUploadDir();
    }

    public function upload(UploadedFile $file): string
    {
        return $file->store($this->dir, ['disk' => $this->disk]);
    }

    public function setDir(string $dir): static
    {
        $this->dir = $dir;

        return $this;
    }

    public function setDisk(string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    protected function defaultUploadDir(): string
    {
        return sprintf("%d/%s", date('Y'), date('m'));
    }

    protected function fullFilePath(string $path)
    {
        return Storage::disk($this->disk)->path($path);
    }
}
