<?php if (basename($_SERVER['PHP_SELF']) == "kategori.php") { ?>
<?php
session_start();
include '../config/db.php';
if ($_SESSION['role'] != 'admin') exit;

$kategori = mysqli_query($conn, "SELECT * FROM kategori");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 p-4 bg-white rounded shadow">
    <h1 class="text-primary text-center mb-4">📚 Kelola Kategori Buku</h1>

    <!-- Tombol Back -->
    <div class="mb-3">
        <a href="dashboard.php" class="btn btn-secondary">&larr; Kembali</a>
    </div>

    <!-- Form Tambah Kategori -->
    <form class="row g-2 mb-4" method="POST" action="../proses/proses_kategori.php">
        <div class="col-sm-9">
            <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori Baru" required>
        </div>
        <div class="col-sm-3">
            <button type="submit" name="tambah" class="btn btn-primary w-100">+ Tambah</button>
        </div>
    </form>

    <!-- Tabel Kategori -->
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php $no = 1; while ($k = mysqli_fetch_assoc($kategori)) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($k['nama_kategori']) ?></td>
                <td>
                    <form action="../proses/proses_kategori.php" method="POST" class="d-flex gap-2 flex-wrap">
                        <input type="hidden" name="id" value="<?= $k['id'] ?>">
                        <input type="text" name="nama_kategori" class="form-control" value="<?= htmlspecialchars($k['nama_kategori']) ?>" required>
                        <button name="update" class="btn btn-warning btn-sm">Edit</button>
                        <a href="../proses/proses_kategori.php?hapus=<?= $k['id'] ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
