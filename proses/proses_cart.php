<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$buku_id = $_GET['buku_id'];

// Cek apakah sudah ada buku yang sama di keranjang
$cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE user_id='$user_id' AND buku_id='$buku_id'");
if (mysqli_num_rows($cek) > 0) {
    mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + 1 WHERE user_id='$user_id' AND buku_id='$buku_id'");
} else {
    mysqli_query($conn, "INSERT INTO keranjang (user_id, buku_id, jumlah) VALUES ('$user_id', '$buku_id', 1)");
}

header("Location: ../user/cart.php");
?>
