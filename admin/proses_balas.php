<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Akses ditolak!";
    exit;
}

// Validasi input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $balasan  = mysqli_real_escape_string($conn, $_POST['balasan']);

    // Simpan ke tabel balasan (pastikan tabel ini sudah dibuat)
    $query = "INSERT INTO balasan (nama, email, pesan_balasan, tanggal) VALUES ('$nama', '$email', '$balasan', NOW())";
    $simpan = mysqli_query($conn, $query);

    if ($simpan) {
        echo "<script>alert('Balasan berhasil disimpan.'); window.location='../admin/pesan.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan balasan.'); window.history.back();</script>";
    }
} else {
    echo "Metode tidak diizinkan.";
}
?>
