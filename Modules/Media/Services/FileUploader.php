<?php

namespace Modules\Media\Services;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploader
{
    protected string $dir;

    protected string $disk = 'local';

    public function __construct()
    {
        $this->dir = $this->defaultUploadDir();
    }

    public function upload(UploadedFile|File $file): UploadedFile|File
    {
        $file->store($this->dir, ['disk' => $this->disk]);

        return $file;
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
        return sprintf('%d/%s', date('Y'), date('m'));
    }

    protected function fullFilePath(string $path)
    {
        return Storage::disk($this->disk)->path($path);
    }
}
