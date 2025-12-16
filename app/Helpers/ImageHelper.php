<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageHelper
{
    public static function upload(
        UploadedFile $file,
        string $folder,
        int $maxWidth = 1200,
        int $quality = 75
    ): string {
        $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        $path = $folder.'/'.$filename;

        $image = Image::make($file)
            ->resize($maxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode(null, $quality);

        Storage::disk('public')->put($path, (string) $image);

        return $path;
    }

    public static function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public static function url(?string $path): string
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return asset('images/no-image.png');
    }
}
