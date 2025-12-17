<?php
// Sesuaikan 'laravel_core' dengan nama folder kamu
$target = '/home/username_cpanel/laravel_core/storage/app/public'; 
$shortcut = '/home/username_cpanel/public_html/storage'; 

// Ganti 'username_cpanel' dengan username cpanel aslimu.
// Kamu bisa melihat path lengkap di sebelah kiri File Manager (home/...)

if(symlink($target, $shortcut)){
    echo "Symlink berhasil dibuat!";
} else {
    echo "Gagal membuat symlink.";
}
?>