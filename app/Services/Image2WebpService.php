<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;

class Image2WebpService
{
    /**
     * Convert uploaded image to WebP with standard width.
     *
     * @param string $path Full path to uploaded file
     * @param int $targetWidth Desired width (default 800px)
     * @param int $quality WebP quality 0-100 (default 80)
     * @return string Relative path to the new WebP file
     * @throws Exception
     */
    public function convert(string $path, int $targetWidth = 800, int $quality = 80): string
    {
        if (!File::exists($path)) {
            throw new Exception("File does not exist: {$path}");
        }

        $creator = $this->imageType($path);
        if (!$creator) {
            throw new Exception("Unsupported image type.");
        }

        // Get original dimensions
        [$width, $height] = getimagesize($path);
        $oldImage = $creator($path);

        // Calculate height to maintain aspect ratio
        $ratio = $width / $height;
        $w = $targetWidth;
        $h = intval($targetWidth / $ratio);

        // Create new true color image
        $newImage = imagecreatetruecolor($w, $h);

        // Fill with white for transparency
        $white = imagecolorallocate($newImage, 255, 255, 255);
        imagefill($newImage, 0, 0, $white);

        // Resample old image into new one
        imagecopyresampled($newImage, $oldImage, 0, 0, 0, 0, $w, $h, $width, $height);

        // Generate unique WebP file name
        $newFile = $this->generateWebpName($path);

        // Save WebP
        imagewebp($newImage, $newFile, $quality);

        // Free memory
        imagedestroy($newImage);
        imagedestroy($oldImage);

        // Delete original if not WebP
        if ($creator !== 'imagecreatefromwebp') {
            File::delete($path);
        }

        // Return relative path (suitable for DB storage)
        return str_replace(public_path('storage') . '/', '', $newFile);
    }

    /**
     * Get the appropriate PHP image creation function for a file.
     */
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

    /**
     * Generate a unique WebP file name in the same directory.
     */
    private function generateWebpName(string $path): string
    {
        $info = pathinfo($path);
        $dir = $info['dirname'];
        $name = $info['filename'] . '-' . Str::random(6) . '.webp';
        return $dir . '/' . $name;
    }
}