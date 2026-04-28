<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class MagicMimeValidation implements ValidationRule
{
    protected array $allowedMimes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
    ];

    protected string $logChannel = 'imageprocessing';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value) {
            return;
        }

        $files = is_array($value) ? $value : [$value];

        foreach ($files as $index => $file) {
            if (!$file || !is_object($file) || !method_exists($file, 'getRealPath')) {
                continue;
            }

            $realPath = $file->getRealPath();

            if (!$realPath || !file_exists($realPath)) {
                $fail("The {$attribute} file at index {$index} is not valid.");
                continue;
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $detectedMime = $finfo->file($realPath);

            if (!in_array($detectedMime, $this->allowedMimes)) {
                Log::channel($this->logChannel)->warning('Magic mime validation failed', [
                    'attribute' => $attribute,
                    'index' => $index,
                    'filename' => $file->getClientOriginalName(),
                    'detected_mime' => $detectedMime,
                    'allowed_mimes' => $this->allowedMimes,
                ]);

                $fail("The {$attribute} file at index {$index} must be a valid image (JPG, JPEG, or PNG). Detected type: {$detectedMime}");
                continue;
            }

            $imageInfo = @getimagesize($realPath);
            if ($imageInfo === false || !str_starts_with($imageInfo['mime'], 'image/')) {
                Log::channel($this->logChannel)->warning('Getimagesize validation failed', [
                    'attribute' => $attribute,
                    'index' => $index,
                    'filename' => $file->getClientOriginalName(),
                    'detected_mime' => $detectedMime,
                ]);

                $fail("The {$attribute} file at index {$index} is not a valid image.");
                continue;
            }

            if ($file->getSize() > 5242880) {
                $fail("The {$attribute} file at index {$index} may not be greater than 5 MB.");
                continue;
            }
        }
    }

    public function setAllowedMimes(array $mimes): self
    {
        $this->allowedMimes = $mimes;
        return $this;
    }
}
