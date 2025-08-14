<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$buku_id = $_POST['buku_id'] ?? 0;

mysqli_query($conn, "DELETE FROM keranjang WHERE user_id='$user_id' AND buku_id='$buku_id'");
header('Location: ../proses/keranjang.php');
exit;
