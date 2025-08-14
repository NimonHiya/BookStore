<?php
session_start();
require_once 'config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BookStore | Toko Buku Online Terpercaya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BookStore - Toko buku online terbaik dengan koleksi lengkap, harga terjangkau, dan pengiriman cepat ke seluruh Indonesia">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #ec4899;
            --accent: #f59e0b;
            --bg: #f8fafc;
            --text: #1e293b;
            --white: #ffffff;
            --gray: #64748b;
            --light-gray: #f1f5f9;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Custom Navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text) !important;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary) !important;
            transform: translateY(-2px);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            margin-top: -80px;
            padding-top: 80px;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="1000,100 1000,0 0,100"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 2rem;
            font-weight: 300;
        }

        .btn-hero {
            background: linear-gradient(135deg, var(--accent), #f97316);
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(245, 158, 11, 0.4);
            color: white;
        }

        /* Features Section */
        .features-section {
            padding: 6rem 0;
            background: white;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem 1.5rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 4rem 0;
            color: white;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            display: block;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        /* About Section */
        .about-section {
            padding: 6rem 0;
            background: var(--light-gray);
        }

        .about-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            text-align: center;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        /* Footer */
        .footer-section {
            background: var(--text);
            color: white;
            padding: 3rem 0 2rem;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .social-links a {
            display: inline-flex;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
            color: white;
        }

        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-2px);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .navbar-custom {
                padding: 0.5rem 0;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-up {
            animation: fadeInUp 0.6s ease forwards;
        }

        /* Scroll animations */
        .scroll-animate {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .scroll-animate.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-book-fill"></i> BookStore
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#home"><i class="bi bi-house"></i> Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#features"><i class="bi bi-star"></i> Fitur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about"><i class="bi bi-info-circle"></i> Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user/login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                </li>
                <li class="nav-item">
                    <a href="user/register.php" class="btn btn-primary-custom ms-2">
                        <i class="bi bi-person-plus"></i> Daftar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="home" class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content animate-up">
                    <h1 class="hero-title">Jelajahi Dunia <span style="color: #f59e0b;">Literasi</span></h1>
                    <p class="hero-subtitle">Temukan ribuan koleksi buku terbaik dari berbagai kategori. Dari novel bestseller hingga buku edukatif, semua ada di sini!</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="user/register.php" class="btn btn-hero">
                            <i class="bi bi-cart-plus"></i> Mulai Belanja
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-arrow-down"></i> Pelajari Lebih
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-image animate-up">
                    <i class="bi bi-book-half" style="font-size: 15rem; color: rgba(255,255,255,0.2);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="stat-item scroll-animate">
                    <span class="stat-number">10K+</span>
                    <span class="stat-label">Koleksi Buku</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item scroll-animate">
                    <span class="stat-number">5K+</span>
                    <span class="stat-label">Pelanggan Puas</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item scroll-animate">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">Kategori Buku</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item scroll-animate">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Layanan Online</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="features-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title scroll-animate">Mengapa Pilih BookStore?</h2>
            <p class="lead text-muted scroll-animate">Kami memberikan pengalaman belanja buku online terbaik</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card scroll-animate">
                    <div class="feature-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h4 class="mb-3">Pengiriman Cepat</h4>
                    <p class="text-muted">Pengiriman ke seluruh Indonesia dengan estimasi 1-3 hari kerja. Gratis ongkir untuk pembelian di atas Rp 100.000</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card scroll-animate">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4 class="mb-3">Transaksi Aman</h4>
                    <p class="text-muted">Sistem pembayaran yang aman dan terpercaya. Berbagai pilihan metode pembayaran untuk kemudahan Anda</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card scroll-animate">
                    <div class="feature-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <h4 class="mb-3">Kualitas Terjamin</h4>
                    <p class="text-muted">Semua buku dijamin original dan berkualitas. Garansi uang kembali jika tidak sesuai ekspektasi</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card scroll-animate">
                    <div class="feature-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h4 class="mb-3">Customer Service</h4>
                    <p class="text-muted">Tim customer service yang responsif dan ramah, siap membantu Anda 24/7 via chat atau telepon</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card scroll-animate">
                    <div class="feature-icon">
                        <i class="bi bi-tags"></i>
                    </div>
                    <h4 class="mb-3">Harga Terjangkau</h4>
                    <p class="text-muted">Harga kompetitif dengan sering ada promo dan diskon menarik. Dapatkan buku favorit dengan harga terbaik</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card scroll-animate">
                    <div class="feature-icon">
                        <i class="bi bi-collection"></i>
                    </div>
                    <h4 class="mb-3">Koleksi Lengkap</h4>
                    <p class="text-muted">Ribuan judul buku dari berbagai genre dan penulis ternama. Selalu update dengan release terbaru</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="about-card scroll-animate">
                    <h2 class="section-title">Tentang BookStore</h2>
                    <p class="lead mb-4">Sejak didirikan, BookStore telah menjadi destinasi utama para pecinta buku di Indonesia</p>
                    <p class="text-muted mb-4">
                        Kami memahami bahwa membaca adalah jendela dunia. Oleh karena itu, BookStore hadir untuk menyediakan 
                        akses mudah ke ribuan koleksi buku berkualitas dari berbagai kategori - mulai dari novel, pendidikan, 
                        bisnis, teknologi, hingga buku anak-anak.
                    </p>
                    <p class="text-muted mb-4">
                        Dengan komitmen untuk memberikan pelayanan terbaik, kami terus berinovasi dalam memberikan pengalaman 
                        belanja online yang nyaman, aman, dan menyenangkan. Tim kami yang berpengalaman siap membantu Anda 
                        menemukan buku yang tepat sesuai kebutuhan.
                    </p>
                    <div class="row text-center mt-5">
                        <div class="col-md-4">
                            <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Tim Profesional</h6>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-lightning-charge-fill text-warning" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Pelayanan Cepat</h6>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-heart-fill text-danger" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Dibuat dengan ❤️</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    </div>
</section>

<!-- Footer -->
<footer class="footer-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="footer-brand">
                    <i class="bi bi-book-fill"></i> BookStore
                </div>
                <p class="text-light mb-4">
                    Toko buku online terpercaya dengan koleksi lengkap dan pelayanan terbaik. 
                    Wujudkan impian literasi Anda bersama kami.
                </p>
                <div class="social-links">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="text-white mb-3">Quick Links</h6>
                <div class="footer-links">
                    <a href="#home" class="d-block mb-2">Beranda</a>
                    <a href="#features" class="d-block mb-2">Fitur</a>
                    <a href="#about" class="d-block mb-2">Tentang</a>
                    <a href="user/login.php" class="d-block mb-2">Login</a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="text-white mb-3">Kategori</h6>
                <div class="footer-links">
                    <a href="#" class="d-block mb-2">Novel</a>
                    <a href="#" class="d-block mb-2">Pendidikan</a>
                    <a href="#" class="d-block mb-2">Bisnis</a>
                    <a href="#" class="d-block mb-2">Teknologi</a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <h6 class="text-white mb-3">Kontak Info</h6>
                <div class="footer-links">
                    <p class="mb-2"><i class="bi bi-geo-alt me-2"></i> Jl. Literasi No. 123, Jakarta</p>
                    <p class="mb-2"><i class="bi bi-telephone me-2"></i> (021) 123-4567</p>
                    <p class="mb-2"><i class="bi bi-envelope me-2"></i> info@bookstore.com</p>
                    <p class="mb-2"><i class="bi bi-clock me-2"></i> 24/7 Online Service</p>
                </div>
            </div>
        </div>
        <hr style="border-color: rgba(255,255,255,0.2);">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-light">
                    &copy; <?= date('Y') ?> BookStore. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0 text-light">
                    Made with <i class="bi bi-heart-fill text-danger"></i> for book lovers
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Navbar background on scroll
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar-custom');
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            navbar.style.boxShadow = '0 2px 25px rgba(0,0,0,0.15)';
        } else {
            navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
        }
    });

    // Scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
            }
        });
    }, observerOptions);

    // Observe all elements with scroll-animate class
    document.querySelectorAll('.scroll-animate').forEach(el => {
        observer.observe(el);
    });

    // Counter animation for stats
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');
        counters.forEach(counter => {
            const target = counter.innerText;
            const numericTarget = parseInt(target.replace(/\D/g, ''));
            const suffix = target.replace(/\d/g, '');
            
            let current = 0;
            const increment = numericTarget / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= numericTarget) {
                    current = numericTarget;
                    clearInterval(timer);
                }
                counter.innerText = Math.floor(current) + suffix;
            }, 20);
        });
    }

    // Trigger counter animation when stats section is visible
    const statsSection = document.querySelector('.stats-section');
    const statsObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    if (statsSection) {
        statsObserver.observe(statsSection);
    }

    // Add loading animation
    window.addEventListener('load', function() {
        document.body.classList.add('loaded');
    });
</script>

</body>
</html>
