<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;

class Image2WebpController
{
    /**
     * Convert uploaded image to WebP and compress it
     *
     * @param string $path  Full path to uploaded file (storage path)
     * @param int $w        Optional width
     * @param int $h        Optional height
     * @param int $q        Quality 0-100
     * @return string       Path to the new WebP file (relative to storage)
     */
    public function convertToWebp(string $path, int $targetWidth = 800, int $q = 50): string
    {
        if (!File::exists($path)) {
            throw new \Exception("File does not exist: {$path}");
        }

        $creator = $this->imageType($path);
        if (!$creator) {
            throw new \Exception("Unsupported image type.");
        }

        // Get original dimensions
        [$width, $height] = getimagesize($path);
        $oldImage = $creator($path);

        // Calculate corresponding height to maintain aspect ratio
        $ratio = $width / $height;
        $w = $targetWidth;
        $h = intval($targetWidth / $ratio);

        $newImage = imagecreatetruecolor($w, $h);

        // White background for transparency (especially PNG/GIF)
        $white = imagecolorallocate($newImage, 255, 255, 255);
        imagefill($newImage, 0, 0, $white);

        // Resample old image into new resized image
        imagecopyresampled($newImage, $oldImage, 0, 0, 0, 0, $w, $h, $width, $height);

        // Save as WebP
        $newFileName = $this->generateWebpName($path);
        dd($newFileName);
        imagewebp($newImage, $newFileName, $q);

        imagedestroy($newImage);
        imagedestroy($oldImage);

        // Delete original
        if ($creator !== 'imagecreatefromwebp') {
            File::delete($path);
        }

        return str_replace(public_path('storage') . '/', '', $newFileName);
    }
    private function imageType(string $path)
    {
        $mime = mime_content_type($path);
        return match($mime) {
            'image/jpeg' => 'imagecreatefromjpeg',
            'image/png'  => 'imagecreatefrompng',
            'image/gif'  => 'imagecreatefromgif',
            'image/webp' => 'imagecreatefromwebp',
            default => null,
        };
    }

    private function generateWebpName(string $path): string
    {
        return preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $path);
    }
}