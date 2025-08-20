<?php
session_start();
include '../config/db.php';

// Tambah kategori
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    
    // Cek apakah kategori sudah ada
    $cek_kategori = mysqli_query($conn, "SELECT * FROM kategori WHERE nama_kategori = '$nama'");
    if (mysqli_num_rows($cek_kategori) > 0) {
        $_SESSION['error'] = "Kategori '$nama' sudah ada!";
    } else {
        mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
        $_SESSION['success'] = "Kategori '$nama' berhasil ditambahkan!";
    }
    header("Location: ../admin/kategori.php");
    exit;
}

// Update kategori
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    
    // Cek apakah kategori sudah ada (kecuali kategori yang sedang diedit)
    $cek_kategori = mysqli_query($conn, "SELECT * FROM kategori WHERE nama_kategori = '$nama' AND id != $id");
    if (mysqli_num_rows($cek_kategori) > 0) {
        $_SESSION['error'] = "Kategori '$nama' sudah ada!";
    } else {
        mysqli_query($conn, "UPDATE kategori SET nama_kategori='$nama' WHERE id=$id");
        $_SESSION['success'] = "Kategori berhasil diperbarui!";
    }
    header("Location: ../admin/kategori.php");
    exit;
}

// Hapus kategori
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    
    // Cek apakah masih ada buku yang menggunakan kategori ini
    $cek_buku = mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM buku WHERE kategori_id = $id");
    $result = mysqli_fetch_assoc($cek_buku);
    
    if ($result['jumlah'] > 0) {
        $_SESSION['error'] = "Kategori tidak dapat dihapus! Masih ada " . $result['jumlah'] . " buku yang menggunakan kategori ini.";
    } else {
        // Ambil nama kategori untuk pesan konfirmasi
        $get_kategori = mysqli_query($conn, "SELECT nama_kategori FROM kategori WHERE id = $id");
        $kategori_data = mysqli_fetch_assoc($get_kategori);
        
        if ($kategori_data) {
            mysqli_query($conn, "DELETE FROM kategori WHERE id=$id");
            $_SESSION['success'] = "Kategori '" . $kategori_data['nama_kategori'] . "' berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Kategori tidak ditemukan!";
        }
    }
    header("Location: ../admin/kategori.php");
    exit;
}
?>
