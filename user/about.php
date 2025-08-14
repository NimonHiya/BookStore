<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - BookStore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(90deg, #4c63d2 0%, #5a67d8 100%);
        }
        .about-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
        }
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .team-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .team-card:hover {
            transform: scale(1.05);
        }
        .stats-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            padding: 3rem 2rem;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(45deg, #28a745, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
                    <a class="nav-link active" href="about.php"><i class="bi bi-info-circle"></i> Tentang Kami</a>
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
<section class="about-header">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Tentang BookStore</h1>
        <p class="lead">Platform toko buku online terpercaya untuk semua kebutuhan literasi Anda</p>
    </div>
</section>

<!-- Main Content -->
<div class="container my-5">
    <!-- Mission Section -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h2 class="fw-bold mb-4">Misi Kami</h2>
            <p class="lead text-muted">BookStore hadir dengan misi menghadirkan pengalaman belanja buku yang mudah, cepat, dan nyaman bagi seluruh pelanggan di Indonesia. Kami percaya bahwa membaca membuka jendela dunia.</p>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <i class="bi bi-collection feature-icon"></i>
                <h4 class="fw-bold">Koleksi Lengkap</h4>
                <p class="text-muted">Beragam kategori buku dari fiksi, non-fiksi, hingga buku pengembangan diri</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <i class="bi bi-truck feature-icon"></i>
                <h4 class="fw-bold">Pengiriman Cepat</h4>
                <p class="text-muted">Sistem pengiriman yang cepat dan aman ke seluruh Indonesia</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <i class="bi bi-shield-check feature-icon"></i>
                <h4 class="fw-bold">Terpercaya</h4>
                <p class="text-muted">Platform belanja buku online yang aman dan terpercaya</p>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="stats-section mb-5">
        <div class="row text-center">
            <div class="col-md-3 mb-3">
                <div class="stat-number">1000+</div>
                <h5>Buku Tersedia</h5>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-number">500+</div>
                <h5>Pelanggan Happy</h5>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-number">50+</div>
                <h5>Kategori Buku</h5>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-number">24/7</div>
                <h5>Customer Support</h5>
            </div>
        </div>
    </div>

    <!-- Story Section -->
    <div class="row mb-5">
        <div class="col-lg-6">
            <h3 class="fw-bold mb-3">Cerita Kami</h3>
            <p class="text-muted">BookStore didirikan dengan visi untuk menjadikan buku lebih mudah diakses oleh semua orang. Kami memulai perjalanan ini dengan keyakinan bahwa setiap orang berhak mendapatkan akses terhadap pengetahuan dan hiburan melalui buku.</p>
            <p class="text-muted">Dari koleksi sederhana hingga menjadi platform dengan ribuan judul buku, kami terus berinovasi untuk memberikan pengalaman terbaik bagi para pecinta buku di Indonesia.</p>
        </div>
        <div class="col-lg-6">
            <div class="team-card">
                <i class="bi bi-people-fill display-1 text-primary mb-3"></i>
                <h4 class="fw-bold">Tim Kami</h4>
                <p class="text-muted">Terdiri dari para profesional yang passionate terhadap dunia literasi dan teknologi</p>
                <div class="row mt-4">
                    <div class="col-6">
                        <i class="bi bi-award-fill text-warning fs-3"></i>
                        <p class="small mb-0">Berpengalaman</p>
                    </div>
                    <div class="col-6">
                        <i class="bi bi-heart-fill text-danger fs-3"></i>
                        <p class="small mb-0">Passionate</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="text-center">
        <h3 class="fw-bold mb-3">Bergabunglah dengan Keluarga BookStore</h3>
        <p class="text-muted mb-4">Temukan buku favorit Anda dan mulai petualangan literasi yang tak terlupakan</p>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
