<?php

namespace App\Services\Shared;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class FileUploadService
{
    public function __construct(
        private readonly AppLogger $logger,
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

        try {
            $storedPath = $filename !== null
                ? $file->storeAs($directory, $filename, $disk)
                : $file->store($directory, $disk);

            $this->logger->info('File stored successfully', [
                'action' => 'file_store',
                'entity_type' => 'file',
                'entity_id' => null,
                'status' => 'success',
                'disk' => $disk,
                'directory' => $directory,
                'stored_path' => $storedPath,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            return $storedPath;
        } catch (\Throwable $exception) {
            $this->logger->error('File upload failed', [
                'action' => 'file_store',
                'entity_type' => 'file',
                'entity_id' => null,
                'status' => 'failed',
                'disk' => $disk,
                'directory' => $directory,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    public function replace(?string $oldPath, UploadedFile $file, string $directory, ?string $filename = null): string
    {
        $this->delete($oldPath);

        $storedPath = $this->store($file, $directory, $filename);

        $this->logger->info('File replaced successfully', [
            'action' => 'file_replace',
            'entity_type' => 'file',
            'entity_id' => null,
            'status' => 'success',
            'disk' => $this->disk(),
            'directory' => $directory,
            'old_path' => $oldPath,
            'stored_path' => $storedPath,
        ]);

        return $storedPath;
    }

    public function delete(?string $path): bool
    {
        if (blank($path)) {
            return false;
        }

        try {
            $deleted = Storage::disk($this->disk())->delete($path);

            if ($deleted) {
                $this->logger->info('File deleted successfully', [
                    'action' => 'file_delete',
                    'entity_type' => 'file',
                    'entity_id' => null,
                    'status' => 'success',
                    'disk' => $this->disk(),
                    'stored_path' => $path,
                ]);
            } else {
                $this->logger->warning('File delete skipped or failed', [
                    'action' => 'file_delete',
                    'entity_type' => 'file',
                    'entity_id' => null,
                    'status' => 'warning',
                    'disk' => $this->disk(),
                    'stored_path' => $path,
                ]);
            }

            return $deleted;
        } catch (\Throwable $exception) {
            $this->logger->error('File delete failed', [
                'action' => 'file_delete',
                'entity_type' => 'file',
                'entity_id' => null,
                'status' => 'failed',
                'disk' => $this->disk(),
                'stored_path' => $path,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
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
