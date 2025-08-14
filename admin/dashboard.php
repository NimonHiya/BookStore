<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$jumlah_buku = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM buku"));
$jumlah_user = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='user'"));
$jumlah_pesanan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pesanan"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - BookStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">📚 BookStore - Admin Panel</a>
        <div>
            <a href="../user/logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<!-- Container -->
<div class="container py-5">

    <h1 class="text-center text-primary mb-5 fw-bold">Dashboard Admin</h1>

    <!-- Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card text-bg-primary shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">📚 Total Buku</h5>
                    <p class="display-6 fw-bold"><?= $jumlah_buku ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-success shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">👤 Total User</h5>
                    <p class="display-6 fw-bold"><?= $jumlah_user ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-warning shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title">🛒 Total Pesanan</h5>
                    <p class="display-6 fw-bold"><?= $jumlah_pesanan ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Manajemen Data -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
            Manajemen Data
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="kategori.php" class="btn btn-outline-primary">📁 Kelola Kategori Buku</a>
                <a href="buku.php" class="btn btn-outline-primary">📖 Kelola Data Buku</a>
                <a href="users.php" class="btn btn-outline-primary">👥 Lihat Daftar User</a>
                <a href="pesanan.php" class="btn btn-outline-primary">🛍️ Lihat Daftar Pesanan</a>
                <a href="pesan.php" class="btn btn-outline-primary">💬 Lihat Pesan User</a>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
