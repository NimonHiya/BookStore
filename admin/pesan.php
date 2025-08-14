<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') exit;

$pesan = mysqli_query($conn, "SELECT k.*, u.nama, u.email FROM kontak k 
                              JOIN users u ON k.user_id = u.id 
                              ORDER BY k.id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan dari User - BookStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-4">

<div class="container">
    <!-- Tombol Back -->
    <div class="mb-3">
        <a href="../admin/dashboard.php" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Judul -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📩 Daftar Pesan dari User</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Pesan</th>
                            <th>Tanggal Kirim</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while($p = mysqli_fetch_assoc($pesan)) { ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($p['nama']) ?></td>
                                <td><?= htmlspecialchars($p['email']) ?></td>
                                <td><?= nl2br(htmlspecialchars($p['pesan'])) ?></td>
                                <td><?= htmlspecialchars($p['tanggal_kirim']) ?></td>
                                <td>
                                    <a href="balas.php?id=<?= $p['id'] ?>" class="btn btn-success btn-sm">
                                        <i class="bi bi-reply"></i> Balas
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS + Icons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>
</html>
