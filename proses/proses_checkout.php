<?php
session_start();
include '../config/db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Cek apakah keranjang tidak kosong
$cek_keranjang = mysqli_query($conn, "SELECT * FROM keranjang WHERE user_id='$user_id'");
if (mysqli_num_rows($cek_keranjang) === 0) {
    header("Location: ../user/cart.php?error=Keranjang kosong");
    exit;
}

// Cek apakah tabel detail_pesanan ada
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'detail_pesanan'");
if (mysqli_num_rows($table_check) == 0) {
    // Buat tabel detail_pesanan jika belum ada
    $create_table = "CREATE TABLE detail_pesanan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pesanan_id INT NOT NULL,
        buku_id INT NOT NULL,
        jumlah INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
        FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE
    )";
    
    if (!mysqli_query($conn, $create_table)) {
        die("Gagal membuat tabel detail_pesanan: " . mysqli_error($conn));
    }
}

// Buat pesanan dengan status langsung "dibayar"
$query_pesanan = mysqli_query($conn, "INSERT INTO pesanan (user_id, tanggal_pesan, status) VALUES ('$user_id', NOW(), 'dibayar')");
if (!$query_pesanan) {
    die("Gagal membuat pesanan: " . mysqli_error($conn));
}
$pesanan_id = mysqli_insert_id($conn);

// Reset data keranjang untuk looping
mysqli_data_seek($cek_keranjang, 0);

// Masukkan detail pesanan dari keranjang
while ($item = mysqli_fetch_assoc($cek_keranjang)) {
    $buku_id = $item['buku_id'];
    $jumlah = $item['jumlah'];

    // Tambahkan ke detail_pesanan
    $insert_detail = mysqli_query($conn, "INSERT INTO detail_pesanan (pesanan_id, buku_id, jumlah) VALUES ('$pesanan_id', '$buku_id', '$jumlah')");
    if (!$insert_detail) {
        die("Gagal menambah detail pesanan: " . mysqli_error($conn));
    }

    // Kurangi stok buku
    $update_stok = mysqli_query($conn, "UPDATE buku SET stok = stok - $jumlah WHERE id = '$buku_id'");
    if (!$update_stok) {
        die("Gagal mengupdate stok: " . mysqli_error($conn));
    }
}

// Kosongkan keranjang setelah checkout
mysqli_query($conn, "DELETE FROM keranjang WHERE user_id='$user_id'");

// Redirect ke halaman utama dengan notifikasi sukses
header("Location: ../user/index.php?checkout=success");
exit;
?>
