<?php

namespace App\Traits;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait Upload
{
    protected string $logChannel = 'imageprocessing';

    public function makeDirectory($path)
    {
        if (file_exists($path)) return true;
        return mkdir($path, 0755, true);
    }

    public function removeFile($path)
    {
        return file_exists($path) && is_file($path) ? @unlink($path) : false;
    }

    public function fileUpload($file, $location, $fileName = null, $size = null, $encodedFormat = null, $encodedQuality = 90, $oldFileName = null, $oldDriver = 'local')
    {
        $activeDisk = config('filesystems.default');

        if (!empty($oldFileName) && Storage::disk($oldDriver)->exists($oldFileName))
            Storage::disk($oldDriver)->delete($oldFileName);

        if (!is_string($file)) {
            $file = new File($file);
            if (str_starts_with($file->getMimeType(), 'image/')) {
                $path = $this->makeImage($activeDisk, $file, $location, $size, $encodedFormat, $encodedQuality, $file->extension());
            } else {
                $path = Storage::disk($activeDisk)->putFileAs($location, $file, $fileName ?? $file->hashName());
            }
        } else {
            if ($this->isImageUrl($file)) {
                $path = $this->makeImage($activeDisk, $file, $location, $size, $encodedFormat, $encodedQuality, pathinfo($file, PATHINFO_FILENAME));
            } else {
                Storage::disk($activeDisk)->put($location, $file);
                $path = $location;
            }
        }

        return [
            'path' => $path,
            'driver' => $activeDisk,
        ];
    }

    protected function makeImage($activeDisk, $file, $location, $size, $encodedFormat, $encodedQuality, $fileExtension)
    {
        $driver = config('image.driver', 'gd');
        $originalName = is_object($file) ? ($file instanceof \Illuminate\Http\UploadedFile ? $file->getClientOriginalName() : $file->getFilename()) : basename($file);

        try {
            $image = Image::make($file);

            if (!empty($size)) {
                $size = explode('x', strtolower($size));
                $image->resize($size[0], $size[1]);
            }

            $path = $location . '/' . Str::random(30) . '.' . ($encodedFormat ?? $fileExtension);
            Storage::disk($activeDisk)->put($path, !empty($encodedFormat) ? $image->encode($encodedFormat, $encodedQuality) : $image->encode());

            return $path;

        } catch (\Exception $e) {
            Log::channel($this->logChannel)->warning('Image processing failed with driver ' . $driver, [
                'file' => $originalName,
                'driver' => $driver,
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
            ]);

            if ($driver === 'gd') {
                $normalized = $this->normalizeImage($file);
                if ($normalized !== null) {
                    Log::channel($this->logChannel)->info('Retrying image processing after normalization', [
                        'file' => $originalName,
                        'driver' => $driver,
                    ]);

                    try {
                        $image = Image::make($normalized);

                        if (!empty($size)) {
                            $size = explode('x', strtolower($size));
                            $image->resize($size[0], $size[1]);
                        }

                        $path = $location . '/' . Str::random(30) . '.' . ($encodedFormat ?? $fileExtension);
                        Storage::disk($activeDisk)->put($path, !empty($encodedFormat) ? $image->encode($encodedFormat, $encodedQuality) : $image->encode());

                        if (is_resource($normalized)) {
                            imagedestroy($normalized);
                        }

                        return $path;

                    } catch (\Exception $retryException) {
                        Log::channel($this->logChannel)->error('Image processing failed even after normalization', [
                            'file' => $originalName,
                            'driver' => $driver,
                            'error' => $retryException->getMessage(),
                            'error_class' => get_class($retryException),
                        ]);

                        if (is_resource($normalized)) {
                            imagedestroy($normalized);
                        }

                        throw $retryException;
                    }
                }
            }

            throw $e;
        }
    }

    protected function normalizeImage($file): mixed
    {
        $realPath = is_object($file) ? $file->getRealPath() : $file;

        if (!$realPath || !file_exists($realPath)) {
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($realPath);

        $imageInfo = @getimagesize($realPath);
        if ($imageInfo === false) {
            return null;
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($realPath);
                break;
            case 'image/png':
                $source = imagecreatefrompng($realPath);
                break;
            default:
                return null;
        }

        if ($source === false) {
            return null;
        }

        $normalized = imagecreatetruecolor($width, $height);

        if ($normalized === false) {
            imagedestroy($source);
            return null;
        }

        if ($mimeType === 'image/png') {
            imagealphablending($normalized, false);
            imagesavealpha($normalized, true);
        }

        if (!imagecopyresampled($normalized, $source, 0, 0, 0, 0, $width, $height, $width, $height)) {
            imagedestroy($source);
            imagedestroy($normalized);
            return null;
        }

        imagedestroy($source);

        return $normalized;
    }

    protected function isImageUrl($url)
    {
        $imageInfo = @getimagesize($url);
        if ($imageInfo != false && str_starts_with($imageInfo['mime'], 'image/'))
            return true;

        return false;
    }

    public function fileDelete($driver = 'local', $old)
    {
        if (!empty($old)) {
            if (Storage::disk($driver)->exists($old)) {
                Storage::disk($driver)->delete($old);
            }
        }
        return 0;
    }
}
