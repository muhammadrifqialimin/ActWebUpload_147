<?php
/**
 * Skrip untuk menangani unggahan file dari permintaan AJAX.
 * Merespons dengan status dalam format JSON.
 */

// Atur header agar browser tahu bahwa responsnya adalah JSON
header('Content-Type: application/json');

// Siapkan array untuk respons default
$response = [
    'success' => false,
    'message' => 'Terjadi kesalahan yang tidak diketahui.'
];

// Pastikan ada file yang diunggah
if (isset($_FILES["fileToUpload"])) {
    
    $target_dir = "uploads/";
    // Sanitasi nama file untuk keamanan
    $safe_filename = preg_replace("/[^a-zA-Z0-9\.\-\_]/", "", basename($_FILES["fileToUpload"]["name"]));
    $target_file = $target_dir . $safe_filename;
    
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Pengecekan 1: Apakah file sudah ada?
    if (file_exists($target_file)) {
        $response['message'] = "Maaf, berkas dengan nama yang sama sudah ada.";
        $uploadOk = 0;
    }

    // Pengecekan 2: Ukuran berkas (misal, maks 5MB = 5000000 bytes)
    if ($uploadOk == 1 && $_FILES["fileToUpload"]["size"] > 5000000) {
        $response['message'] = "Maaf, ukuran berkas Anda terlalu besar (maks 5MB).";
        $uploadOk = 0;
    }

    // Pengecekan 3: Format berkas yang diizinkan
    $allowed_types = ["jpg", "jpeg", "png", "gif", "pdf", "doc", "docx", "zip", "txt"];
    if ($uploadOk == 1 && !in_array($fileType, $allowed_types)) {
        $response['message'] = "Maaf, hanya berkas " . implode(", ", $allowed_types) . " yang diperbolehkan.";
        $uploadOk = 0;
    }

    // Pengecekan 4 (opsional): Pastikan itu benar-benar gambar jika ekstensinya gambar
    $image_types = ["jpg", "jpeg", "png", "gif"];
    if ($uploadOk == 1 && in_array($fileType, $image_types)) {
        if (getimagesize($_FILES["fileToUpload"]["tmp_name"]) === false) {
            $response['message'] = "File terlihat seperti gambar, tetapi isinya bukan gambar yang valid.";
            $uploadOk = 0;
        }
    }

    // Jika semua pengecekan lolos ($uploadOk masih 1)
    if ($uploadOk == 1) {
        // Coba pindahkan file yang diunggah ke direktori tujuan
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $response['success'] = true;
            $response['message'] = "Berkas ". htmlspecialchars($safe_filename). " berhasil diunggah.";
        } else {
            $response['message'] = "Maaf, terjadi kesalahan server saat memindahkan berkas Anda.";
        }
    }
    // Jika ada pengecekan yang gagal, pesan error sudah diatur sebelumnya.

} else {
    $response['message'] = 'Tidak ada file yang diterima oleh server.';
}

// Kirim respons dalam format JSON ke JavaScript
echo json_encode($response);
exit();
?>