<?php
/**
 * Skrip untuk menghapus file dan merespons permintaan AJAX.
 * Mengirimkan kembali status dalam format JSON.
 */

// Atur header agar browser tahu bahwa responsnya adalah JSON
header('Content-Type: application/json');

// Siapkan array untuk respons default
$response = [
    'success' => false,
    'message' => 'Permintaan tidak valid atau file tidak ditemukan.'
];

// 1. Validasi Input: Pastikan parameter 'file' dikirim melalui metode POST
if (isset($_POST['file'])) {
    
    // 2. Keamanan: Gunakan basename() untuk mencegah serangan Directory Traversal.
    $file_name = basename($_POST['file']);
    $file_path = 'uploads/' . $file_name;

    // 3. Pengecekan: Pastikan nama file tidak kosong dan file benar-benar ada
    if (!empty($file_name) && file_exists($file_path)) {
        
        // 4. Aksi Utama: Coba hapus file menggunakan fungsi unlink()
        if (unlink($file_path)) {
            // Jika berhasil, ubah isi respons menjadi sukses
            $response['success'] = true;
            $response['message'] = "Berkas '" . htmlspecialchars($file_name) . "' berhasil dihapus.";
        } else {
            // Jika gagal, isi respons dengan pesan error
            $response['message'] = "Error: Gagal menghapus berkas. Periksa perizinan pada folder 'uploads'.";
        }

    } else {
        // Jika file tidak ditemukan, isi respons dengan pesan error
        $response['message'] = 'Error: File tidak ditemukan atau sudah dihapus.';
    }

}

// 5. Kirim Respons: Keluarkan array respons sebagai string JSON
echo json_encode($response);
exit();

?>