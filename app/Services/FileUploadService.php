<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Allowed file types with their MIME types
     */
    protected array $allowedTypes = [
        'pdf' => ['application/pdf'],
        'doc' => ['application/msword'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        'zip' => ['application/zip', 'application/x-zip-compressed'],
        'jpg' => ['image/jpeg', 'image/jpg'],
        'jpeg' => ['image/jpeg', 'image/jpg'],
        'png' => ['image/png'],
    ];

    /**
     * Maximum file size in bytes (20MB default)
     */
    protected int $maxSize = 20971520; // 20MB

    /**
     * Upload a file to storage
     */
    public function upload(UploadedFile $file, string $directory = 'uploads'): array
    {
        // Validate file
        $this->validateFile($file);

        // Generate unique filename
        $filename = $this->generateUniqueFilename($file);

        // Store file
        $path = $file->storeAs($directory, $filename, 'public');

        return [
            'path' => $path,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
        ];
    }

    /**
     * Upload multiple files
     */
    public function uploadMultiple(array $files, string $directory = 'uploads'): array
    {
        $uploadedFiles = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $uploadedFiles[] = $this->upload($file, $directory);
            }
        }

        return $uploadedFiles;
    }

    /**
     * Delete a file from storage
     */
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Delete multiple files
     */
    public function deleteMultiple(array $paths): bool
    {
        $allDeleted = true;

        foreach ($paths as $path) {
            if (!$this->delete($path)) {
                $allDeleted = false;
            }
        }

        return $allDeleted;
    }

    /**
     * Generate a signed URL for temporary file access
     */
    public function getSignedUrl(string $path, int $minutes = 60): string
    {
        return Storage::disk('public')->temporaryUrl($path, now()->addMinutes($minutes));
    }

    /**
     * Get file URL
     */
    public function getUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Check if file exists
     */
    public function exists(string $path): bool
    {
        return Storage::disk('public')->exists($path);
    }

    /**
     * Validate uploaded file
     */
    protected function validateFile(UploadedFile $file): void
    {
        // Check file size
        if ($file->getSize() > $this->maxSize) {
            throw new \InvalidArgumentException(
                'File size exceeds maximum allowed size of ' . ($this->maxSize / 1048576) . 'MB'
            );
        }

        // Check file type
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();

        if (!isset($this->allowedTypes[$extension])) {
            throw new \InvalidArgumentException(
                'File type not allowed. Allowed types: ' . implode(', ', array_keys($this->allowedTypes))
            );
        }

        if (!in_array($mimeType, $this->allowedTypes[$extension])) {
            throw new \InvalidArgumentException(
                'Invalid MIME type for ' . $extension . ' file'
            );
        }
    }

    /**
     * Generate unique filename
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $random = Str::random(8);

        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Set maximum file size
     */
    public function setMaxSize(int $bytes): self
    {
        $this->maxSize = $bytes;
        return $this;
    }

    /**
     * Add allowed file type
     */
    public function addAllowedType(string $extension, array $mimeTypes): self
    {
        $this->allowedTypes[strtolower($extension)] = $mimeTypes;
        return $this;
    }
}
