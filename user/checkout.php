<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    echo "Akses ditolak. Silakan <a href='login.php'>login</a> terlebih dahulu.";
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - BookStore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(90deg, #4c63d2 0%, #5a67d8 100%);
        }
        .checkout-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
        }
        .payment-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }
        .payment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        .payment-option {
            border: 3px solid #e9ecef;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        .payment-option:hover {
            border-color: #6c5ce7;
            background: white;
            transform: scale(1.02);
        }
        .payment-option input[type="radio"]:checked + .option-content {
            color: #6c5ce7;
        }
        .payment-option:has(input[type="radio"]:checked) {
            border-color: #6c5ce7;
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        }
        .option-content {
            display: flex;
            align-items: center;
        }
        .option-icon {
            font-size: 2rem;
            margin-right: 1rem;
            width: 60px;
            text-align: center;
        }
        .btn-checkout {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 50px;
            padding: 1rem 3rem;
            font-size: 1.2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-checkout:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }
        .security-badge {
            background: linear-gradient(45deg, #17a2b8, #6f42c1);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
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

<!-- Header -->
<section class="checkout-header">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-3"><i class="bi bi-credit-card"></i> Checkout</h1>
        <p class="lead">Langkah terakhir untuk menyelesaikan pesanan Anda</p>
    </div>
</section>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="payment-card">
                <div class="text-center mb-4">
                    <div class="security-badge d-inline-block">
                        <i class="bi bi-shield-check"></i> Pembayaran Aman
                    </div>
                    <h3 class="fw-bold text-primary">Pilih Metode Pembayaran</h3>
                    <p class="text-muted">Silakan pilih metode pembayaran yang Anda inginkan</p>
                </div>

                <form action="../proses/proses_checkout.php" method="POST">
                    <div class="payment-methods">
                        <label class="payment-option">
                            <input type="radio" name="metode" value="QRIS" required style="display: none;">
                            <div class="option-content">
                                <div class="option-icon">
                                    <i class="bi bi-qr-code-scan text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">QRIS</h5>
                                    <p class="text-muted mb-0">Scan QR Code untuk pembayaran instan</p>
                                    <small class="text-success">✓ Mudah dan Cepat</small>
                                </div>
                            </div>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="metode" value="BCA" style="display: none;">
                            <div class="option-content">
                                <div class="option-icon">
                                    <i class="bi bi-bank text-info"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Transfer Bank (BCA)</h5>
                                    <p class="text-muted mb-0">Transfer melalui rekening Bank Central Asia</p>
                                    <small class="text-success">✓ Aman dan Terpercaya</small>
                                </div>
                            </div>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="metode" value="COD" style="display: none;">
                            <div class="option-content">
                                <div class="option-icon">
                                    <i class="bi bi-truck text-warning"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Bayar di Tempat (COD)</h5>
                                    <p class="text-muted mb-0">Bayar saat buku tiba di lokasi Anda</p>
                                    <small class="text-success">✓ Bayar Setelah Terima</small>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-checkout text-white">
                            <i class="bi bi-check-circle me-2"></i>Konfirmasi & Bayar Sekarang
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <a href="cart.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Keranjang
                    </a>
                </div>

                <div class="row mt-5">
                    <div class="col-md-4 text-center">
                        <i class="bi bi-shield-check fs-2 text-success"></i>
                        <h6 class="mt-2">Keamanan Terjamin</h6>
                        <small class="text-muted">Data Anda dilindungi dengan enkripsi SSL</small>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-truck fs-2 text-primary"></i>
                        <h6 class="mt-2">Pengiriman Cepat</h6>
                        <small class="text-muted">Pesanan diproses dalam 1-2 hari kerja</small>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-headset fs-2 text-info"></i>
                        <h6 class="mt-2">Dukungan 24/7</h6>
                        <small class="text-muted">Tim customer service siap membantu</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Add interactivity to payment options
document.querySelectorAll('.payment-option').forEach(option => {
    option.addEventListener('click', function() {
        // Remove active class from all options
        document.querySelectorAll('.payment-option').forEach(opt => {
            opt.classList.remove('active');
        });
        
        // Add active class to clicked option
        this.classList.add('active');
        
        // Check the radio button
        this.querySelector('input[type="radio"]').checked = true;
    });
});
</script>

</body>
</html>
