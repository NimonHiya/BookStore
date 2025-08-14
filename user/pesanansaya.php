<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil semua pesanan milik user
$pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE user_id = $user_id ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya - BookStore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h1 class="text-center mb-4 text-primary fw-bold">📦 Riwayat Pesanan Buku</h1>

    <?php if (mysqli_num_rows($pesanan) > 0): ?>
        <?php while ($p = mysqli_fetch_assoc($pesanan)): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="mb-1"><strong>Tanggal Pesan:</strong> <?= htmlspecialchars($p['tanggal_pesan']) ?></p>
                            <p class="mb-0">
                                <strong>Status:</strong> 
                                <?php
                                $statusClass = [
                                    'Belum Bayar' => 'bg-warning text-dark',
                                    'Dibayar' => 'bg-info text-dark',
                                    'Diproses' => 'bg-success text-white',
                                    'Selesai' => 'bg-primary text-white',
                                    'Batal' => 'bg-danger text-white'
                                ];
                                $class = $statusClass[$p['status']] ?? 'bg-secondary text-white';
                                ?>
                                <span class="badge <?= $class ?>">
                                    <?= htmlspecialchars($p['status']) ?>
                                </span>
                            </p>
                        </div>
                        <div>
                            <?php if (in_array(strtolower($p['status']), ['dibayar', 'diproses', 'selesai'])): ?>
                                <a href="struk.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm" target="_blank">
                                    <i class="bi bi-receipt"></i> Lihat Struk
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php
                    // Cek detail pesanan
                    $detail = mysqli_query($conn, "
                        SELECT detail_pesanan.*, buku.judul, buku.harga 
                        FROM detail_pesanan 
                        JOIN buku ON detail_pesanan.buku_id = buku.id 
                        WHERE detail_pesanan.pesanan_id = {$p['id']}
                    ");
                    ?>

                    <?php if ($detail && mysqli_num_rows($detail) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Judul Buku</th>
                                        <th>Jumlah</th>
                                        <th>Harga Satuan</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0; ?>
                                    <?php while ($d = mysqli_fetch_assoc($detail)): 
                                        $subtotal = $d['jumlah'] * $d['harga'];
                                        $total += $subtotal;
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($d['judul']) ?></td>
                                            <td><?= $d['jumlah'] ?></td>
                                            <td>Rp<?= number_format($d['harga'], 0, ',', '.') ?></td>
                                            <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                <tfoot class="fw-bold">
                                    <tr>
                                        <td colspan="3" class="text-end">Total</td>
                                        <td>Rp<?= number_format($total, 0, ',', '.') ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle"></i> <strong>Info:</strong> Belum ada detail item untuk pesanan ini.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info">Belum ada pesanan yang kamu buat.</div>
    <?php endif; ?>

    <a href="index.php" class="btn btn-primary">← Kembali ke Beranda</a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
