<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    echo "Akses ditolak. Silakan <a href='login.php'>login</a> terlebih dahulu.";
    exit();
}


$user_id = $_SESSION['user_id'];

// === PROSES TAMBAH/KURANG/HAPUS ===
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $buku_id = $_POST['buku_id'] ?? 0;
    $aksi = $_POST['aksi'] ?? '';

    if ($aksi == 'tambah' || $aksi == 'kurang') {
        $cek = mysqli_query($conn, "
            SELECT k.jumlah, b.stok 
            FROM keranjang k
            JOIN buku b ON k.buku_id = b.id
            WHERE k.user_id='$user_id' AND k.buku_id='$buku_id'
        ");
        $data = mysqli_fetch_assoc($cek);

        if ($data) {
            $jumlah = $data['jumlah'];
            $stok = $data['stok'];

            if ($aksi == 'tambah' && $jumlah < $stok) {
                $jumlah++;
            } elseif ($aksi == 'kurang') {
                $jumlah = max(1, $jumlah - 1); // minimal 1
            }

            mysqli_query($conn, "UPDATE keranjang SET jumlah='$jumlah' WHERE user_id='$user_id' AND buku_id='$buku_id'");
        }
    } elseif ($aksi == 'hapus') {
        mysqli_query($conn, "DELETE FROM keranjang WHERE user_id='$user_id' AND buku_id='$buku_id'");
    }

    header("Location: cart.php");
    exit;
}

// === AMBIL DATA KERANJANG ===
$cart = mysqli_query($conn, "
    SELECT keranjang.*, buku.judul, buku.harga, buku.stok, buku.gambar
    FROM keranjang
    JOIN buku ON keranjang.buku_id = buku.id
    WHERE user_id='$user_id'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - BookStore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(90deg, #4c63d2 0%, #5a67d8 100%);
        }
        .cart-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
        }
        .book-thumbnail {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .no-image-thumb {
            width: 80px;
            height: 100px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            color: #6c757d;
            font-size: 0.8rem;
        }
        .qty-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-qty {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        .total-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 2rem;
        }
        .checkout-btn {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .checkout-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.4);
        }
        .empty-cart {
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="bi bi-book"></i> BookStore
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="bi bi-house"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php"><i class="bi bi-info-circle"></i> Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php"><i class="bi bi-envelope"></i> Kontak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="cart.php"><i class="bi bi-cart3"></i> Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pesanansaya.php"><i class="bi bi-bag-check"></i> Pesanan Saya</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-warning" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Header -->
<section class="cart-header">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-2"><i class="bi bi-cart3"></i> Keranjang Belanja</h1>
        <p class="lead">Kelola pesanan Anda sebelum checkout</p>
    </div>
</section>

<div class="container my-5">
    <?php if (mysqli_num_rows($cart) > 0): ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Pesanan</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php $total = 0; mysqli_data_seek($cart, 0); while ($c = mysqli_fetch_assoc($cart)) {
                            $sub = $c['harga'] * $c['jumlah'];
                            $total += $sub; ?>
                            <div class="border-bottom p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($c['gambar']) && file_exists("../assets/img/" . $c['gambar'])) { ?>
                                                <img src="../assets/img/<?= $c['gambar'] ?>" alt="<?= $c['judul'] ?>" class="book-thumbnail me-3">
                                            <?php } else { ?>
                                                <div class="no-image-thumb me-3">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            <?php } ?>
                                            <div>
                                                <h6 class="mb-1"><?= htmlspecialchars($c['judul']) ?></h6>
                                                <p class="text-success fw-bold mb-0">Rp <?= number_format($c['harga']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="qty-controls">
                                            <form action="cart.php" method="POST" class="d-inline">
                                                <input type="hidden" name="buku_id" value="<?= $c['buku_id'] ?>">
                                                <input type="hidden" name="aksi" value="kurang">
                                                <button type="submit" class="btn btn-outline-secondary btn-qty">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                            </form>
                                            <span class="badge bg-primary px-3 py-2"><?= $c['jumlah'] ?></span>
                                            <form action="cart.php" method="POST" class="d-inline">
                                                <input type="hidden" name="buku_id" value="<?= $c['buku_id'] ?>">
                                                <input type="hidden" name="aksi" value="tambah">
                                                <button type="submit" class="btn btn-outline-primary btn-qty" <?= $c['jumlah'] >= $c['stok'] ? 'disabled' : '' ?>>
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <small class="text-muted">Stok: <?= $c['stok'] ?></small>
                                    </div>
                                    <div class="col-md-2">
                                        <p class="fw-bold text-success mb-0">Rp <?= number_format($sub) ?></p>
                                    </div>
                                    <div class="col-md-1">
                                        <form action="cart.php" method="POST" class="d-inline">
                                            <input type="hidden" name="buku_id" value="<?= $c['buku_id'] ?>">
                                            <input type="hidden" name="aksi" value="hapus">
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin menghapus item ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="total-section">
                    <h4 class="mb-3"><i class="bi bi-calculator"></i> Ringkasan Pesanan</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Harga:</span>
                        <span class="fw-bold text-success fs-4">Rp <?= number_format($total) ?></span>
                    </div>
                    <hr>
                    <a href="checkout.php" class="btn checkout-btn text-white w-100 mb-3">
                        <i class="bi bi-credit-card"></i> Checkout Sekarang
                    </a>
                    <a href="index.php" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left"></i> Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-cart">
            <div class="text-center">
                <i class="bi bi-cart-x display-1 text-muted"></i>
                <h3 class="mt-3">Keranjang Anda Kosong</h3>
                <p class="text-muted">Belum ada buku yang ditambahkan ke keranjang</p>
                <a href="index.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-shop"></i> Mulai Belanja
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
