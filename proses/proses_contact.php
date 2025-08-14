<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$pesan = $_POST['pesan'];

mysqli_query($conn, "INSERT INTO kontak (user_id, pesan) VALUES ('$user_id', '$pesan')");
header("Location: ../user/contact.php?status=success");
?>
