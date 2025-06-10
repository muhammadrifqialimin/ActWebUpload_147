<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ... sisa kode Anda dimulai di sini ...
// Pastikan parameter 'file' ada
if (isset($_GET['file'])) {
    $file_name = basename($_GET['file']); // basename() untuk keamanan
    $file_path = 'uploads/' . $file_name;

    // Periksa apakah file benar-benar ada dan berada di dalam direktori 'uploads'
    if (!empty($file_name) && file_exists($file_path)) {
        // Atur header untuk memaksa unduhan
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        
        // Baca file dan kirimkan ke output
        flush(); // Flush sistem output buffer
        readfile($file_path);
        exit();
    } else {
        // Jika file tidak ditemukan, kirim pesan error
        die("Error: File tidak ditemukan.");
    }
} else {
    die("Error: Permintaan tidak valid.");
}
?>