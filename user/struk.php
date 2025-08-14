<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$pesanan_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data pesanan
$pesanan_query = mysqli_query($conn, "
    SELECT pesanan.*, users.nama, users.email 
    FROM pesanan 
    JOIN users ON pesanan.user_id = users.id 
    WHERE pesanan.id = $pesanan_id AND pesanan.user_id = $user_id
");

if (mysqli_num_rows($pesanan_query) == 0) {
    header("Location: pesanansaya.php");
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
    <title>Struk Pesanan #<?= $pesanan_id ?> - BookStore</title>
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
            max-width: 600px;
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
        .divider {
            border-top: 2px dashed #dee2e6;
            margin: 1rem 0;
        }
        .total-section {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container my-4">
    <div class="struk-container">
        <!-- Header Struk -->
        <div class="struk-header">
            <h2 class="mb-1"><i class="bi bi-book"></i> BookStore</h2>
            <p class="mb-0">Toko Buku Online Terpercaya</p>
            <small>Jl. Literasi No. 123, Jakarta | Tel: (021) 123-4567</small>
        </div>

        <!-- Informasi Pesanan -->
        <div class="p-4">
            <div class="row mb-3">
                <div class="col-6">
                    <h5 class="text-primary mb-3"><i class="bi bi-receipt"></i> STRUK PEMBELIAN</h5>
                    <p class="mb-1"><strong>No. Pesanan:</strong> #<?= str_pad($pesanan_id, 6, '0', STR_PAD_LEFT) ?></p>
                    <p class="mb-1"><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($pesanan['tanggal_pesan'])) ?></p>
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
                </div>
                <div class="col-6 text-end">
                    <h6 class="text-muted">Pelanggan:</h6>
                    <p class="mb-1"><strong><?= htmlspecialchars($pesanan['nama']) ?></strong></p>
                    <p class="mb-1 text-muted"><?= htmlspecialchars($pesanan['email']) ?></p>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Detail Pembelian -->
            <h6 class="mb-3"><i class="bi bi-list-ul"></i> Detail Pembelian:</h6>
            
            <?php if (mysqli_num_rows($detail) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
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
                                        <small class="text-muted">oleh <?= htmlspecialchars($d['penulis']) ?></small>
                                    </td>
                                    <td class="text-center"><?= $d['jumlah'] ?></td>
                                    <td class="text-end">Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
                                    <td class="text-end">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>

                <!-- Total -->
                <div class="total-section">
                    <div class="row">
                        <div class="col-8">
                            <h5 class="mb-0">TOTAL PEMBAYARAN:</h5>
                        </div>
                        <div class="col-4 text-end">
                            <h4 class="text-success mb-0"><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></h4>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> Detail pesanan tidak ditemukan.
                </div>
            <?php endif; ?>

            <div class="divider"></div>

            <!-- Footer -->
            <div class="text-center text-muted">
                <p class="mb-1"><i class="bi bi-heart-fill text-danger"></i> Terima kasih telah berbelanja di BookStore</p>
                <p class="mb-1">Semoga buku-buku pilihan Anda memberikan manfaat</p>
                <small>Struk ini dicetak otomatis pada <?= date('d/m/Y H:i') ?></small>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="text-center mt-4 no-print">
        <button onclick="window.print()" class="btn btn-primary me-2">
            <i class="bi bi-printer"></i> Cetak Struk
        </button>
        <a href="pesanansaya.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
