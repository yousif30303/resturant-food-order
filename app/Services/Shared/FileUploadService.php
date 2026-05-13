<?php

namespace App\Services\Shared;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class FileUploadService
{
    public function __construct(
        private readonly ?string $disk = null
    ) {
    }

    public static function directories(): array
    {
        return array_values(config('uploads.paths', []));
    }

    public static function path(string $key): string
    {
        $path = config("uploads.paths.{$key}");

        if (! is_string($path) || $path === '') {
            throw new InvalidArgumentException("Unsupported upload path key [{$key}].");
        }

        return $path;
    }

    public static function allowedImageMimes(): array
    {
        return config('uploads.allowed_image_mimes', []);
    }

    public static function maxSize(?string $key = null): int
    {
        if ($key !== null) {
            return (int) config("uploads.max_size.{$key}", config('uploads.max_size.default', 2048));
        }

        return (int) config('uploads.max_size.default', 2048);
    }

    public function store(UploadedFile $file, string $directory, ?string $filename = null): string
    {
        $disk = $this->disk();

        $this->guardDirectory($directory);

        if ($filename !== null) {
            return $file->storeAs($directory, $filename, $disk);
        }

        return $file->store($directory, $disk);
    }

    public function replace(?string $oldPath, UploadedFile $file, string $directory, ?string $filename = null): string
    {
        $this->delete($oldPath);

        return $this->store($file, $directory, $filename);
    }

    public function delete(?string $path): bool
    {
        if (blank($path)) {
            return false;
        }

        return Storage::disk($this->disk())->delete($path);
    }

    public function exists(?string $path): bool
    {
        if (blank($path)) {
            return false;
        }

        return Storage::disk($this->disk())->exists($path);
    }

    public function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return Storage::disk($this->disk())->url($path);
    }

    public function disk(): string
    {
        return $this->disk ?? config('uploads.disk', config('filesystems.default', 'public'));
    }

    private function guardDirectory(string $directory): void
    {
        if (! in_array($directory, self::directories(), true)) {
            throw new InvalidArgumentException("Unsupported upload directory [{$directory}].");
        }
    }
}
