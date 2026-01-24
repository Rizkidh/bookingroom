<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class ImageHelper
{
    public static function upload(UploadedFile $file, string $folder): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $destinationPath = public_path($folder);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Compress and resize image using Intervention Image
        \Intervention\Image\Facades\Image::make($file)
            ->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($destinationPath . '/' . $filename, 75);

        return $folder . '/' . $filename;
    }

    public static function delete(?string $path): void
    {
        if (!$path) {
            return;
        }

        $fullPath = public_path($path);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
