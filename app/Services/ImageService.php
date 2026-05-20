<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageService
{
    /**
     * Upload, resize, convert to WebP, and generate a thumbnail.
     *
     * Optimized for batch uploads: reads file once, scales down immediately
     * to free GD memory, then derives thumbnail from the smaller image.
     */
    public static function upload(UploadedFile $file, string $folder, ?int $width = null): array
    {
        if (!$file->isValid()) {
            throw new \RuntimeException('Invalid image upload.');
        }

        $config = config("images.{$folder}", []);
        $mainWidth = $width ?? ($config['width'] ?? 1200);
        $thumbW = $config['thumb_width'] ?? 400;
        $thumbH = $config['thumb_height'] ?? 300;
        $mainQual = config('images.quality.main', 85);
        $thumbQual = config('images.quality.thumb', 80);

        $filename = Str::uuid() . '.webp';
        $thumbName = 'thumbs/' . $filename;

        $manager = new ImageManager(new Driver());

        // 1. Read huge original image
        $original = $manager->read($file->getRealPath());

        // 2. Scale down to a new instance
        $mainImage = $original->scaleDown(width: $mainWidth);

        // 3. IMMEDIATELY free the huge original image to clear ~150MB+
        unset($original);
        gc_collect_cycles();

        // 4. Save main image
        $mainEncoded = $mainImage->toWebp(quality: $mainQual);
        Storage::disk('public')->put("{$folder}/{$filename}", (string) $mainEncoded);
        unset($mainEncoded);

        // 5. Crop thumbnail from the already-small mainImage
        $thumbImage = $mainImage->cover($thumbW, $thumbH);

        // 6. Free the mainImage
        unset($mainImage);

        // 7. Save thumbnail
        $thumbEncoded = $thumbImage->toWebp(quality: $thumbQual);
        Storage::disk('public')->put("{$folder}/{$thumbName}", (string) $thumbEncoded);

        // 8. Clean up
        unset($thumbImage, $thumbEncoded, $manager);
        gc_collect_cycles();

        return [
            'image' => "{$folder}/{$filename}",
            'thumb' => "{$folder}/{$thumbName}",
        ];
    }

    /**
     * Delete an image and its thumbnail from storage.
     */
    public static function delete(?string $path): void
    {
        if (!$path) {
            return;
        }

        // Skip external URLs — only delete local storage paths
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        $disk = Storage::disk('public');

        if ($disk->exists($path)) {
            $disk->delete($path);
        }

        $dir = dirname($path);
        $file = basename($path);

        // Check old-style prefix thumb
        $oldThumb = ($dir !== '.' ? $dir . '/' : '') . 'thumb_' . $file;
        if ($disk->exists($oldThumb)) {
            $disk->delete($oldThumb);
        }

        // Check new-style thumbs/ subfolder
        $newThumb = ($dir !== '.' ? $dir . '/' : '') . 'thumbs/' . $file;
        if ($disk->exists($newThumb)) {
            $disk->delete($newThumb);
        }
    }
}
