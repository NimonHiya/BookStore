<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - BookStore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 450px;
        }
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .register-header i {
            font-size: 4rem;
            color: #5a67d8;
            margin-bottom: 1rem;
        }
        .form-control {
            border-radius: 15px;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            background: rgba(255, 255, 255, 0.8);
        }
        .form-control:focus {
            border-color: #5a67d8;
            box-shadow: 0 0 0 0.2rem rgba(90, 103, 216, 0.25);
            background: white;
        }
        .btn-register {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 15px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 15px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
        }
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }
        .divider span {
            background: rgba(255, 255, 255, 0.95);
            padding: 0 1rem;
            color: #64748b;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="register-card">
                <div class="register-header">
                    <i class="bi bi-person-plus-fill"></i>
                    <h2 class="fw-bold text-primary">Daftar Akun</h2>
                    <p class="text-muted">Bergabunglah dengan BookStore</p>
                </div>
                
                <form action="../proses/proses_register.php" method="POST">
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0" style="border-radius: 15px 0 0 15px; border: 2px solid #e2e8f0;">
                                <i class="bi bi-person text-muted"></i>
                            </span>
                            <input type="text" name="nama" class="form-control border-start-0" placeholder="Nama Lengkap" required style="border-radius: 0 15px 15px 0;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0" style="border-radius: 15px 0 0 15px; border: 2px solid #e2e8f0;">
                                <i class="bi bi-envelope text-muted"></i>
                            </span>
                            <input type="email" name="email" class="form-control border-start-0" placeholder="Email Aktif" required style="border-radius: 0 15px 15px 0;">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0" style="border-radius: 15px 0 0 15px; border: 2px solid #e2e8f0;">
                                <i class="bi bi-lock text-muted"></i>
                            </span>
                            <input type="password" name="password" class="form-control border-start-0" placeholder="Password (min. 6 karakter)" minlength="6" required style="border-radius: 0 15px 15px 0;">
                        </div>
                        <small class="text-muted">Password minimal 6 karakter</small>
                    </div>
                    
                    <button type="submit" name="register" class="btn btn-register text-white w-100 mb-3">
                        <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                    </button>
                </form>
                
                <div class="divider">
                    <span>atau</span>
                </div>
                
                <div class="text-center mb-3">
                    <p class="text-muted">Sudah punya akun?</p>
                    <a href="login.php" class="btn btn-outline-primary w-100" style="border-radius: 15px;">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login Sekarang
                    </a>
                </div>
                
                <div class="text-center">
                    <a href="../index.php" class="btn back-btn">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
