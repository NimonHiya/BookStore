<?php if (basename($_SERVER['PHP_SELF']) == "buku.php") { ?>
<?php
session_start();
include '../config/db.php';
if ($_SESSION['role'] != 'admin') exit;

// Ambil data buku dan kategori
$buku = mysqli_query($conn, "SELECT buku.*, kategori.nama_kategori FROM buku JOIN kategori ON buku.kategori_id = kategori.id");
$kategori = mysqli_query($conn, "SELECT * FROM kategori");

// Cek apakah sedang mode edit
$edit_mode = false;
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_query = mysqli_query($conn, "SELECT * FROM buku WHERE id = $edit_id");
    $edit_data = mysqli_fetch_assoc($edit_query);
    $edit_mode = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Buku - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">

    <!-- Tombol Back -->
    <div class="mb-3">
        <a href="dashboard.php" class="btn btn-secondary btn-sm">⬅ Kembali ke Dashboard</a>
    </div>

    <h1 class="text-primary text-center fw-bold mb-4">📘 Kelola Buku</h1>

    <!-- Alert Info -->
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <div>ℹ️ Buku hanya dapat dihapus jika stok sudah <strong>0</strong>. Kurangi stok menjadi 0 terlebih dahulu sebelum menghapus buku.</div>
    </div>

    <!-- Form Tambah/Edit Buku -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <?= $edit_mode ? '✏️ Edit Buku' : '➕ Tambah Buku' ?>
        </div>
        <div class="card-body">
            <form method="POST" action="../proses/proses_buku.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?? '' ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" name="judul" class="form-control" required value="<?= $edit_data['judul'] ?? '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Penulis</label>
                        <input type="text" name="penulis" class="form-control" required value="<?= $edit_data['penulis'] ?? '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control" required value="<?= $edit_data['harga'] ?? '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" required value="<?= $edit_data['stok'] ?? '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <?php mysqli_data_seek($kategori, 0); while ($k = mysqli_fetch_assoc($kategori)) { ?>
                                <option value="<?= $k['id'] ?>" <?= (isset($edit_data) && $edit_data['kategori_id'] == $k['id']) ? 'selected' : '' ?>>
                                    <?= $k['nama_kategori'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gambar</label>
                        <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.gif" <?= $edit_mode ? '' : 'required' ?>>
                        <div class="form-text">Format yang diizinkan: JPG, JPEG, PNG, GIF</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" required><?= $edit_data['deskripsi'] ?? '' ?></textarea>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" name="<?= $edit_mode ? 'update' : 'tambah' ?>" class="btn btn-success">
                        <?= $edit_mode ? '💾 Update Buku' : '+ Tambah Buku' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Buku -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            📚 Daftar Buku
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; mysqli_data_seek($buku, 0); while ($b = mysqli_fetch_assoc($buku)) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <?php if (!empty($b['gambar']) && file_exists("../assets/img/" . $b['gambar'])) { ?>
                                <img src="../assets/img/<?= $b['gambar'] ?>" alt="<?= $b['judul'] ?>" class="img-thumbnail" style="width: 80px; height: 100px; object-fit: cover;">
                            <?php } else { ?>
                                <span class="text-muted">No Image</span>
                            <?php } ?>
                        </td>
                        <td><?= $b['judul'] ?></td>
                        <td><?= $b['penulis'] ?></td>
                        <td><?= $b['nama_kategori'] ?></td>
                        <td>Rp <?= number_format($b['harga']) ?></td>
                        <td class="<?= ($b['stok'] > 0) ? 'text-danger fw-bold' : 'text-success fw-bold' ?>">
                            <?= $b['stok'] ?>
                        </td>
                        <td>
                            <a href="?edit=<?= $b['id'] ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                            <?php if ($b['stok'] == 0) { ?>
                                <a href="../proses/proses_buku.php?hapus=<?= $b['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus buku ini?')">🗑️ Hapus</a>
                            <?php } else { ?>
                                <button class="btn btn-secondary btn-sm" disabled>🗑️ Hapus</button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
