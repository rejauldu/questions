<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Exception;

class Image2WebpService
{
    /**
     * @param string $path Absolute path to source image
     * @param int $targetWidth Resize width (0 for original)
     * @param int $quality Compression quality (0-100)
     * @param string|int|null $suffix Optional string to append to filename (e.g. 1, 2, 3)
     */
    public function convert(string $path, int $targetWidth = 0, int $quality = 80, $suffix = null): string
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

        // Logic for resizing
        if ($targetWidth <= 0) {
            $w = $width;
            $h = $height;
        } else {
            $ratio = $width / $height;
            $w = $targetWidth;
            $h = intval($targetWidth / $ratio);
        }

        $newImage = imagecreatetruecolor($w, $h);

        // Handle transparency
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefilledrectangle($newImage, 0, 0, $w, $h, $transparent);

        imagecopyresampled($newImage, $oldImage, 0, 0, 0, 0, $w, $h, $width, $height);

        // Pass the suffix to the name generator
        $newFile = $this->generateWebpName($path, $suffix);
        
        imagewebp($newImage, $newFile, $quality);

        imagedestroy($newImage);
        imagedestroy($oldImage);

        return $newFile;
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

    /**
     * Generates the WebP path, incorporating an optional suffix.
     */
    private function generateWebpName(string $path, $suffix = null): string
    {
        $info = pathinfo($path);
        $dir  = $info['dirname'];
        $base = $info['filename'];
        
        // Append suffix if provided (e.g., "myimage" becomes "myimage-1")
        if (!is_null($suffix)) {
            $base .= '-' . $suffix;
        }
        
        $filename = $base . '.webp';
        $fullPath = $dir . '/' . $filename;
    
        // If file already exists, add a timestamp to prevent overwriting
        if (File::exists($fullPath)) {
            $filename = $base . '-' . time() . '.webp';
            $fullPath = $dir . '/' . $filename;
        }
    
        return $fullPath;
    }
}