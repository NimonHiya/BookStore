<?php
include '../config/db.php';

// Tambah kategori
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_kategori'];
    mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
    header("Location: ../admin/kategori.php");
}

// Update kategori
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_kategori'];
    mysqli_query($conn, "UPDATE kategori SET nama_kategori='$nama' WHERE id=$id");
    header("Location: ../admin/kategori.php");
}

// Hapus kategori
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kategori WHERE id=$id");
    header("Location: ../admin/kategori.php");
}
?>
