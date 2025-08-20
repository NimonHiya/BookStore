<?php if (basename($_SERVER['PHP_SELF']) == "kategori.php") { ?>
<?php
session_start();
include '../config/db.php';
if ($_SESSION['role'] != 'admin') exit;

// Query untuk mendapatkan kategori beserta jumlah buku
$kategori = mysqli_query($conn, "
    SELECT k.*, COUNT(b.id) as jumlah_buku 
    FROM kategori k 
    LEFT JOIN buku b ON k.id = b.kategori_id 
    GROUP BY k.id 
    ORDER BY k.nama_kategori
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 p-4 bg-white rounded shadow">
    <h1 class="text-primary text-center mb-4">
        <i class="bi bi-tags-fill"></i> Kelola Kategori Buku
    </h1>

    <!-- Notifikasi -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Tombol Back -->
    <div class="mb-3">
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Statistik Kategori -->
    <?php
    $total_kategori = mysqli_query($conn, "SELECT COUNT(*) as total FROM kategori");
    $total_kategori_data = mysqli_fetch_assoc($total_kategori);
    
    $kategori_terpakai = mysqli_query($conn, "SELECT COUNT(DISTINCT kategori_id) as terpakai FROM buku WHERE kategori_id IS NOT NULL");
    $kategori_terpakai_data = mysqli_fetch_assoc($kategori_terpakai);
    ?>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <i class="bi bi-tags-fill fs-1"></i>
                    <h3><?= $total_kategori_data['total'] ?></h3>
                    <p class="mb-0">Total Kategori</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <i class="bi bi-check-circle-fill fs-1"></i>
                    <h3><?= $kategori_terpakai_data['terpakai'] ?></h3>
                    <p class="mb-0">Kategori Terpakai</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <i class="bi bi-exclamation-circle-fill fs-1"></i>
                    <h3><?= $total_kategori_data['total'] - $kategori_terpakai_data['terpakai'] ?></h3>
                    <p class="mb-0">Kategori Kosong</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Tambah Kategori -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Kategori Baru</h5>
        </div>
        <div class="card-body">
            <form class="row g-2" method="POST" action="../proses/proses_kategori.php">
                <div class="col-sm-9">
                    <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori Baru" required>
                </div>
                <div class="col-sm-3">
                    <button type="submit" name="tambah" class="btn btn-primary w-100">
                        <i class="bi bi-plus"></i> Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Kategori -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Kategori</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th width="50">No</th>
                            <th>Kategori</th>
                            <th width="120" class="text-center">Jumlah Buku</th>
                            <th width="350">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; while ($k = mysqli_fetch_assoc($kategori)) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <strong><?= htmlspecialchars($k['nama_kategori']) ?></strong>
                                <?php if ($k['jumlah_buku'] > 0): ?>
                                    <br><small class="text-muted">
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-<?= $k['jumlah_buku'] > 0 ? 'success' : 'secondary' ?>">
                                    <?= $k['jumlah_buku'] ?> buku
                                </span>
                            </td>
                            <td>
                                <form action="../proses/proses_kategori.php" method="POST" class="d-flex gap-2 flex-wrap align-items-center">
                                    <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                    <div class="input-group" style="max-width: 200px;">
                                        <input type="text" name="nama_kategori" class="form-control form-control-sm" 
                                               value="<?= htmlspecialchars($k['nama_kategori']) ?>" required>
                                        <button name="update" class="btn btn-warning btn-sm" title="Edit Kategori">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                    
                                    <?php if ($k['jumlah_buku'] > 0): ?>
                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" 
                                              title="Kategori ini tidak dapat dihapus karena masih ada <?= $k['jumlah_buku'] ?> buku yang menggunakannya">
                                            <button type="button" class="btn btn-danger btn-sm" disabled>
                                                <i class="bi bi-shield-lock"></i> Terkunci
                                            </button>
                                        </span>
                                        
                                    <?php else: ?>
                                        <a href="../proses/proses_kategori.php?hapus=<?= $k['id'] ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin hapus kategori \'<?= htmlspecialchars($k['nama_kategori']) ?>\'?\nTindakan ini tidak dapat dibatalkan!')"
                                           title="Hapus Kategori">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (mysqli_num_rows($kategori) == 0): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1"></i><br>
                                Belum ada kategori yang tersedia
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Konfirmasi untuk tombol hapus yang tidak disabled
    const deleteButtons = document.querySelectorAll('a[href*="hapus"]');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            const kategoriName = this.getAttribute('onclick').match(/'([^']+)'/)[1];
            if (!confirm(`Yakin ingin menghapus kategori "${kategoriName}"?\n\nTindakan ini tidak dapat dibatalkan!`)) {
                e.preventDefault();
            }
        });
    });
    
    // Focus pada input kategori baru
    const inputKategori = document.querySelector('input[name="nama_kategori"]');
    if (inputKategori) {
        inputKategori.focus();
    }
    
    // Highlight kategori kosong
    const emptyCategories = document.querySelectorAll('.badge.bg-secondary');
    emptyCategories.forEach(function(badge) {
        if (badge.textContent.includes('0 buku')) {
            badge.closest('tr').classList.add('table-warning');
        }
    });
});
</script>
</body>
</html>
<?php } ?>
