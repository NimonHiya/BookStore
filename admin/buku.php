<?php if (basename($_SERVER['PHP_SELF']) == "buku.php") { ?>
<?php
session_start();
include '../config/db.php';
if ($_SESSION['role'] != 'admin') exit;

// Ambil data buku dan kategori
$buku = mysqli_query($conn, "SELECT buku.*, kategori.nama_kategori FROM buku JOIN kategori ON buku.kategori_id = kategori.id");
$kategori = mysqli_query($conn, "SELECT * FROM kategori");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Buku - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="text-primary fw-bold">
                <i class="bi bi-book-fill"></i> Daftar Buku
            </h1>
            <p class="text-muted mb-0">Kelola semua koleksi buku di toko</p>
        </div>
        <div>
        
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Alert Info -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <div>
                    <strong>Informasi:</strong> Buku hanya dapat dihapus jika stok sudah <strong>0</strong>. 
                    Kurangi stok menjadi 0 terlebih dahulu sebelum menghapus buku.
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center py-3">
                    <h5 class="text-success mb-1"><?= mysqli_num_rows($buku) ?></h5>
                    <small class="text-muted">Total Buku</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <i class="bi bi-plus-circle text-primary" style="font-size: 2rem;"></i>
                    <h6 class="mt-2">Tambah Buku</h6>
                    <a href="tambah_buku.php" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-right"></i> Tambah
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info h-100">
                <div class="card-body text-center">
                    <i class="bi bi-tag text-info" style="font-size: 2rem;"></i>
                    <h6 class="mt-2">Kategori</h6>
                    <a href="kategori.php" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-arrow-right"></i> Kelola
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning h-100">
                <div class="card-body text-center">
                    <i class="bi bi-box text-warning" style="font-size: 2rem;"></i>
                    <h6 class="mt-2">Stok Habis</h6>
                    <small class="text-muted">
                        <?php
                        $stok_habis = mysqli_query($conn, "SELECT COUNT(*) as count FROM buku WHERE stok = 0");
                        $habis = mysqli_fetch_assoc($stok_habis);
                        echo $habis['count'] . " buku";
                        ?>
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success h-100">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up text-success" style="font-size: 2rem;"></i>
                    <h6 class="mt-2">Total Stok</h6>
                    <small class="text-muted">
                        <?php
                        $total_stok = mysqli_query($conn, "SELECT SUM(stok) as total FROM buku");
                        $stok = mysqli_fetch_assoc($total_stok);
                        echo number_format($stok['total']) . " unit";
                        ?>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Buku -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-table"></i> Daftar Buku
            </h5>
            <div class="d-flex gap-2">
                <!-- Search -->
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control form-control-sm" placeholder="Cari buku..." id="searchInput">
                    <button class="btn btn-outline-light btn-sm" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <!-- Filter -->
                <select class="form-select form-select-sm" style="width: 150px;" id="filterKategori">
                    <option value="">Semua Kategori</option>
                    <?php 
                    mysqli_data_seek($kategori, 0); 
                    while ($k = mysqli_fetch_assoc($kategori)): 
                    ?>
                        <option value="<?= htmlspecialchars($k['nama_kategori']) ?>">
                            <?= htmlspecialchars($k['nama_kategori']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
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
                            <a href="edit_buku.php?id=<?= $b['id'] ?>" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <?php if ($b['stok'] == 0) { ?>
                                <a href="../proses/proses_buku.php?hapus=<?= $b['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            <?php } else { ?>
                                <button class="btn btn-secondary btn-sm" disabled title="Stok harus 0 untuk menghapus">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
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

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    filterTable();
});

// Filter functionality
document.getElementById('filterKategori').addEventListener('change', function() {
    filterTable();
});

function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const filterValue = document.getElementById('filterKategori').value.toLowerCase();
    const table = document.querySelector('table tbody');
    const rows = table.getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        if (cells.length > 0) {
            const judul = cells[2].textContent.toLowerCase();
            const penulis = cells[3].textContent.toLowerCase();
            const kategori = cells[4].textContent.toLowerCase();
            
            const matchSearch = judul.includes(searchValue) || penulis.includes(searchValue);
            const matchFilter = filterValue === '' || kategori.includes(filterValue);
            
            if (matchSearch && matchFilter) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
    
    // Update row numbers
    updateRowNumbers();
}

function updateRowNumbers() {
    const table = document.querySelector('table tbody');
    const visibleRows = Array.from(table.getElementsByTagName('tr')).filter(row => row.style.display !== 'none');
    
    visibleRows.forEach((row, index) => {
        const firstCell = row.getElementsByTagName('td')[0];
        if (firstCell) {
            firstCell.textContent = index + 1;
        }
    });
}
</script>

</body>
</html>
<?php } ?>
