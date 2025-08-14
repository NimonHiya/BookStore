<?php
session_start();
include '../config/db.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php"); 
    exit();
}

// Pencarian
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
$buku = mysqli_query($conn, "SELECT * FROM buku WHERE judul LIKE '%$cari%' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore - Beranda</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
        }
        .book-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .book-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .no-image {
            width: 100%;
            height: 250px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            border: 2px dashed #dee2e6;
        }
        .navbar-custom {
            background: linear-gradient(90deg, #4c63d2 0%, #5a67d8 100%);
        }
        .search-section {
            background: #f8f9fa;
            padding: 2rem 0;
        }
        .btn-cart {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-cart:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
        }
        .price-text {
            font-size: 1.2rem;
            font-weight: bold;
            color: #28a745;
        }
        .stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .footer-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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
                    <a class="nav-link active" href="index.php"><i class="bi bi-house"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php"><i class="bi bi-info-circle"></i> Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php"><i class="bi bi-envelope"></i> Kontak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php"><i class="bi bi-cart3"></i> Cart</a>
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

<!-- Hero Section -->
<section class="hero-section">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Selamat Datang di BookStore</h1>
        <p class="lead">Temukan buku-buku terbaik untuk menambah wawasan Anda</p>
    </div>
</section>

<!-- Search Section -->
<section class="search-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="GET" class="d-flex">
                    <input type="text" name="cari" class="form-control form-control-lg me-2" 
                           placeholder="Cari buku berdasarkan judul..." value="<?= htmlspecialchars($cari) ?>">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Books Section -->
<div class="container my-5">
    <?php if (isset($_GET['checkout']) && $_GET['checkout'] == 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> 
            <strong>Checkout berhasil!</strong> Pesanan Anda telah dikonfirmasi dan sedang diproses.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <?php if (mysqli_num_rows($buku) > 0): ?>
            <?php while ($b = mysqli_fetch_assoc($buku)) { ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card book-card h-100 shadow-sm">
                        <div class="position-relative">
                            <?php if (!empty($b['gambar']) && file_exists("../assets/img/" . $b['gambar'])) { ?>
                                <img src="../assets/img/<?= $b['gambar'] ?>" alt="<?= $b['judul'] ?>" class="card-img-top book-image">
                            <?php } else { ?>
                                <div class="no-image">
                                    <div class="text-center">
                                        <i class="bi bi-image fs-1"></i>
                                        <p class="mb-0">Tidak ada gambar</p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($b['stok'] > 0): ?>
                                <span class="badge bg-success stock-badge">Stok: <?= $b['stok'] ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger stock-badge">Habis</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($b['judul']) ?></h5>
                            <p class="card-text text-muted">
                                <i class="bi bi-person"></i> <?= htmlspecialchars($b['penulis']) ?>
                            </p>
                            <p class="price-text">Rp <?= number_format($b['harga']) ?></p>
                            <div class="mt-auto">
                                <?php if ($b['stok'] > 0): ?>
                                    <a href="../proses/proses_cart.php?buku_id=<?= $b['id'] ?>" class="btn btn-cart btn-success w-100">
                                        <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="bi bi-x-circle"></i> Stok Habis
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle fs-3"></i>
                    <h4 class="alert-heading">Tidak ada buku ditemukan</h4>
                    <p>Maaf, tidak ada buku yang sesuai dengan pencarian Anda.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer class="footer-custom mt-5 py-4">
    <div class="container text-center">
        <p class="mb-0">&copy; <?= date('Y') ?> BookStore. All rights reserved.</p>
        <p class="mb-0">Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> untuk pecinta buku</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
