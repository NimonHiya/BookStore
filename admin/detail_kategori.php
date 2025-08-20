<?php
session_start();
include '../config/db.php';

// Cek role admin
if ($_SESSION['role'] != 'admin') {
    header("Location: ../user/login.php");
    exit;
}

// Ambil ID kategori
$kategori_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($kategori_id == 0) {
    header("Location: kategori.php");
    exit;
}

// Ambil data kategori
$kategori_query = mysqli_query($conn, "SELECT * FROM kategori WHERE id = $kategori_id");
$kategori = mysqli_fetch_assoc($kategori_query);

if (!$kategori) {
    $_SESSION['error'] = "Kategori tidak ditemukan!";
    header("Location: kategori.php");
    exit;
}

// Ambil buku-buku dalam kategori ini
$buku_query = mysqli_query($conn, "
    SELECT b.*, u.username as penulis_name 
    FROM buku b 
    LEFT JOIN users u ON b.penulis_id = u.id 
    WHERE b.kategori_id = $kategori_id 
    ORDER BY b.judul
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kategori: <?= htmlspecialchars($kategori['nama_kategori']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-tag-fill"></i> 
                        Detail Kategori: <?= htmlspecialchars($kategori['nama_kategori']) ?>
                    </h4>
                    <a href="kategori.php" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <?php
                    $jumlah_buku = mysqli_num_rows($buku_query);
                    mysqli_data_seek($buku_query, 0); // Reset pointer
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Kategori:</h6>
                            <ul class="list-unstyled">
                                <li><strong>ID:</strong> <?= $kategori['id'] ?></li>
                                <li><strong>Nama:</strong> <?= htmlspecialchars($kategori['nama_kategori']) ?></li>
                                <li><strong>Jumlah Buku:</strong> 
                                    <span class="badge bg-<?= $jumlah_buku > 0 ? 'success' : 'secondary' ?>">
                                        <?= $jumlah_buku ?> buku
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Status:</h6>
                            <?php if ($jumlah_buku > 0): ?>
                                <div class="alert alert-warning mb-2">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <strong>Kategori tidak dapat dihapus</strong><br>
                                    Masih ada <?= $jumlah_buku ?> buku yang menggunakan kategori ini.
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success mb-2">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <strong>Kategori dapat dihapus</strong><br>
                                    Tidak ada buku yang menggunakan kategori ini.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Buku -->
            <?php if ($jumlah_buku > 0): ?>
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-book-fill"></i> 
                        Buku dalam Kategori (<?= $jumlah_buku ?>)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th width="80">Cover</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th width="100">Harga</th>
                                    <th width="80">Stok</th>
                                    <th width="100">Status</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while ($buku = mysqli_fetch_assoc($buku_query)): 
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?php if ($buku['cover'] && file_exists("../assets/img/" . $buku['cover'])): ?>
                                            <img src="../assets/img/<?= htmlspecialchars($buku['cover']) ?>" 
                                                 class="img-thumbnail" style="width: 50px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 60px; font-size: 12px;">
                                                No Cover
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($buku['judul']) ?></strong>
                                        <?php if ($buku['deskripsi']): ?>
                                            <br><small class="text-muted">
                                                <?= htmlspecialchars(substr($buku['deskripsi'], 0, 80)) ?>
                                                <?= strlen($buku['deskripsi']) > 80 ? '...' : '' ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($buku['penulis_name'] ?? 'Tidak diketahui') ?></td>
                                    <td>Rp <?= number_format($buku['harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $buku['stok'] > 0 ? 'success' : 'danger' ?>">
                                            <?= $buku['stok'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($buku['stok'] > 0): ?>
                                            <span class="badge bg-success">Tersedia</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Habis</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="edit_buku.php?id=<?= $buku['id'] ?>" 
                                               class="btn btn-warning btn-sm" title="Edit Buku">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="../proses/proses_buku.php?hapus=<?= $buku['id'] ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin hapus buku \'<?= htmlspecialchars($buku['judul']) ?>\'?')"
                                               title="Hapus Buku">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <h4 class="text-muted mt-3">Tidak Ada Buku</h4>
                    <p class="text-muted">Kategori ini belum memiliki buku apapun.</p>
                    <div class="mt-4">
                        <a href="tambah_buku.php?kategori=<?= $kategori['id'] ?>" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Tambah Buku ke Kategori Ini
                        </a>
                        <a href="../proses/proses_kategori.php?hapus=<?= $kategori['id'] ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Yakin hapus kategori \'<?= htmlspecialchars($kategori['nama_kategori']) ?>\'?')">
                            <i class="bi bi-trash"></i> Hapus Kategori
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
