<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Exception;

class Image2WebpService
{
    public function convert(string $path, int $targetWidth = 0, int $quality = 80): string
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

        // --- LOGIC CHANGE START ---
        // If targetWidth is 0, use original dimensions
        if ($targetWidth <= 0) {
            $w = $width;
            $h = $height;
        } else {
            // Calculate height to maintain aspect ratio
            $ratio = $width / $height;
            $w = $targetWidth;
            $h = intval($targetWidth / $ratio);
        }
        // --- LOGIC CHANGE END ---

        $newImage = imagecreatetruecolor($w, $h);

        // Handle transparency for PNG/WebP
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefilledrectangle($newImage, 0, 0, $w, $h, $transparent);

        imagecopyresampled($newImage, $oldImage, 0, 0, 0, 0, $w, $h, $width, $height);

        $newFile = $this->generateWebpName($path);
        imagewebp($newImage, $newFile, $quality);

        imagedestroy($newImage);
        imagedestroy($oldImage);

        // IMPORTANT: Return absolute path so your Controller rename/exists logic works
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

    private function generateWebpName(string $path): string
    {
        $info = pathinfo($path);
        $dir  = $info['dirname'];
        $base = $info['filename'];
        
        $filename = $base . '.webp';
        $fullPath = $dir . '/' . $filename;
    
        if (File::exists($fullPath)) {
            $filename = $base . '-' . time() . '.webp';
            $fullPath = $dir . '/' . $filename;
        }
    
        return $fullPath;
    }
}