<?php
// Atur header untuk memberitahu browser bahwa ini adalah respons JSON
header('Content-Type: application/json');

$upload_dir = 'uploads/';
$files_data = [];

// Daftar ekstensi file yang dianggap sebagai gambar
$image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];

// Periksa apakah direktori ada
if (is_dir($upload_dir)) {
    // Baca isi direktori, abaikan '.' dan '..'
    $files = array_diff(scandir($upload_dir), array('.', '..'));

    // Urutkan file berdasarkan waktu modifikasi (terbaru di atas)
    usort($files, function($a, $b) use ($upload_dir) {
        return filemtime($upload_dir . $b) - filemtime($upload_dir . $a);
    });

    foreach ($files as $file) {
        $file_path = $upload_dir . $file;
        $file_size = filesize($file_path);
        $file_size_formatted = round($file_size / 1024, 2) . ' KB';
        
        // Dapatkan ekstensi file
        $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

        // Cek apakah file ini adalah gambar berdasarkan ekstensinya
        $is_image = in_array($file_extension, $image_extensions);

        // Tambahkan informasi file ke dalam array, termasuk URL dan status gambar
        $files_data[] = [
            'name' => htmlspecialchars($file),
            'size' => $file_size_formatted,
            'url' => $file_path, // Kirim path lengkap untuk src gambar
            'is_image' => $is_image // Kirim status apakah ini gambar atau bukan
        ];
    }
}

// Keluarkan array sebagai string JSON
echo json_encode($files_data);
?>