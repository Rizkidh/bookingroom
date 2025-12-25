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


/*<?php
namespace App\Helpers;
use Illuminate\Http\UploadedFile;
class ImageHelper
{
    public static function upload(UploadedFile $file, string $folder): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // JANGAN gunakan public_path($folder), tapi gunakan ini:
        // base_path() mengarah ke /home/username/bookingroom
        // Kita naik satu tingkat (..) lalu masuk ke public_html
        $destinationPath = base_path('../public_html/' . $folder);

        // Pastikan folder tujuan ada
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Pindahkan file
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
\*/// IGNORE THIS BLOCK - HOSTING VERSION


