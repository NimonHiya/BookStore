<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$buku_id = $_POST['buku_id'] ?? 0;
$aksi    = $_POST['aksi'] ?? '';

$cek = mysqli_query($conn, "SELECT jumlah FROM keranjang WHERE user_id='$user_id' AND buku_id='$buku_id'");
$data = mysqli_fetch_assoc($cek);

if ($data) {
    $jumlah = $data['jumlah'];
    if ($aksi === 'tambah') {
        $jumlah++;
    } elseif ($aksi === 'kurang') {
        $jumlah = max(1, $jumlah - 1); // minimal 1
    }
    mysqli_query($conn, "UPDATE keranjang SET jumlah='$jumlah' WHERE user_id='$user_id' AND buku_id='$buku_id'");
}

header('Location: ../proses/keranjang.php');
exit;
