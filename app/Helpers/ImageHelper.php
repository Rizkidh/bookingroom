<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class ImageHelper
{
    public static function upload(UploadedFile $file, string $folder): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // LANGSUNG ke public_html
        $destinationPath = public_path($folder);

        // Folder SUDAH ada (dibuat manual di cPanel)
        $file->move($destinationPath, $filename);

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
