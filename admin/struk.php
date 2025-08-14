<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$pesanan_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data pesanan
$pesanan_query = mysqli_query($conn, "
    SELECT pesanan.*, users.nama, users.email 
    FROM pesanan 
    JOIN users ON pesanan.user_id = users.id 
    WHERE pesanan.id = $pesanan_id
");

if (mysqli_num_rows($pesanan_query) == 0) {
    header("Location: pesanan.php");
    exit;
}

$pesanan = mysqli_fetch_assoc($pesanan_query);

// Ambil detail pesanan
$detail = mysqli_query($conn, "
    SELECT detail_pesanan.*, buku.judul, buku.harga, buku.penulis
    FROM detail_pesanan 
    JOIN buku ON detail_pesanan.buku_id = buku.id 
    WHERE detail_pesanan.pesanan_id = $pesanan_id
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan #<?= $pesanan_id ?> - Admin BookStore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            .container { max-width: none !important; }
            body { font-size: 12px; }
        }
        .struk-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .struk-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .admin-badge {
            background: linear-gradient(45deg, #dc3545, #fd7e14);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        .divider {
            border-top: 2px dashed #dee2e6;
            margin: 1rem 0;
        }
        .total-section {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
        }
        .admin-info {
            background: #e3f2fd;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #2196f3;
        }
    </style>
</head>
<body class="bg-light">

<div class="container my-4">
    <div class="struk-container">
        <!-- Header Struk -->
        <div class="struk-header">
            <div class="admin-badge d-inline-block">
                <i class="bi bi-shield-check"></i> ADMIN VIEW
            </div>
            <h2 class="mb-1"><i class="bi bi-book"></i> BookStore</h2>
            <p class="mb-0">Toko Buku Online Terpercaya</p>
            <small>Jl. Literasi No. 123, Jakarta | Tel: (021) 123-4567</small>
        </div>

        <!-- Informasi Admin -->
        <div class="p-4">
            <div class="admin-info mb-4">
                <h6 class="mb-2"><i class="bi bi-person-gear"></i> Informasi Admin</h6>
                <div class="row">
                    <div class="col-6">
                        <small><strong>Dilihat oleh:</strong> <?= htmlspecialchars($_SESSION['nama']) ?></small>
                    </div>
                    <div class="col-6 text-end">
                        <small><strong>Waktu akses:</strong> <?= date('d/m/Y H:i') ?></small>
                    </div>
                </div>
            </div>

            <!-- Informasi Pesanan -->
            <div class="row mb-3">
                <div class="col-6">
                    <h5 class="text-primary mb-3"><i class="bi bi-receipt"></i> DETAIL PESANAN</h5>
                    <p class="mb-1"><strong>No. Pesanan:</strong> #<?= str_pad($pesanan_id, 6, '0', STR_PAD_LEFT) ?></p>
                    <p class="mb-1"><strong>Tanggal Pesan:</strong> <?= date('d/m/Y H:i', strtotime($pesanan['tanggal_pesan'])) ?></p>
                    <p class="mb-1"><strong>Status:</strong> 
                        <?php
                        $statusClass = [
                            'belum bayar' => 'bg-warning text-dark',
                            'dibayar' => 'bg-info text-dark',
                            'diproses' => 'bg-success text-white',
                            'selesai' => 'bg-primary text-white',
                            'batal' => 'bg-danger text-white'
                        ];
                        $class = $statusClass[strtolower($pesanan['status'])] ?? 'bg-secondary text-white';
                        ?>
                        <span class="badge <?= $class ?>">
                            <?= htmlspecialchars($pesanan['status']) ?>
                        </span>
                    </p>
                    <p class="mb-1"><strong>User ID:</strong> #<?= $pesanan['user_id'] ?></p>
                </div>
                <div class="col-6 text-end">
                    <h6 class="text-muted">Data Pelanggan:</h6>
                    <p class="mb-1"><strong><?= htmlspecialchars($pesanan['nama']) ?></strong></p>
                    <p class="mb-1 text-muted"><?= htmlspecialchars($pesanan['email']) ?></p>
                    <small class="text-muted">ID User: <?= $pesanan['user_id'] ?></small>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Detail Pembelian -->
            <h6 class="mb-3"><i class="bi bi-list-ul"></i> Rincian Item Pesanan:</h6>
            
            <?php if (mysqli_num_rows($detail) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0; 
                            mysqli_data_seek($detail, 0);
                            while ($d = mysqli_fetch_assoc($detail)): 
                                $subtotal = $d['jumlah'] * $d['harga'];
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($d['judul']) ?></strong><br>
                                        <small class="text-muted">oleh <?= htmlspecialchars($d['penulis']) ?></small><br>
                                        <small class="text-info">ID Buku: <?= $d['buku_id'] ?></small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary"><?= $d['jumlah'] ?></span>
                                    </td>
                                    <td class="text-end">Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
                                    <td class="text-end">
                                        <strong>Rp <?= number_format($subtotal, 0, ',', '.') ?></strong>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>

                <!-- Total -->
                <div class="total-section">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h5 class="mb-0">TOTAL NILAI PESANAN:</h5>
                            <small class="text-muted">Total dari <?= mysqli_num_rows($detail) ?> item berbeda</small>
                        </div>
                        <div class="col-4 text-end">
                            <h3 class="text-success mb-0"><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></h3>
                        </div>
                    </div>
                </div>

                <!-- Admin Actions (hanya tampil di layar, tidak di print) -->
                <div class="admin-info mt-4 no-print">
                    <h6 class="mb-3"><i class="bi bi-gear"></i> Aksi Admin:</h6>
                    <div class="d-flex gap-2">
                        <?php if ($pesanan['status'] == 'dibayar'): ?>
                            <a href="pesanan.php?kirim=<?= $pesanan_id ?>" class="btn btn-success btn-sm">
                                <i class="bi bi-truck"></i> Proses Kirim
                            </a>
                            <a href="pesanan.php?batal=<?= $pesanan_id ?>" class="btn btn-danger btn-sm" 
                               onclick="return confirm('Yakin ingin batalkan pesanan ini?')">
                                <i class="bi bi-x-circle"></i> Batalkan
                            </a>
                        <?php endif; ?>
                        <button onclick="window.print()" class="btn btn-primary btn-sm">
                            <i class="bi bi-printer"></i> Cetak untuk Arsip
                        </button>
                    </div>
                </div>

            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> Detail pesanan tidak ditemukan atau kosong.
                </div>
            <?php endif; ?>

            <div class="divider"></div>

            <!-- Footer -->
            <div class="text-center text-muted">
                <p class="mb-1"><i class="bi bi-shield-check-fill text-success"></i> Dokumen ini diakses dari panel admin</p>
                <small>Dicetak/dilihat pada <?= date('d/m/Y H:i') ?> WIB</small>
            </div>
        </div>
    </div>

    <!-- Tombol Navigasi -->
    <div class="text-center mt-4 no-print">
        <a href="pesanan.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Data Pesanan
        </a>
        <a href="dashboard.php" class="btn btn-primary">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
