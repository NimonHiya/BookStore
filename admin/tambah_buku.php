<?php
session_start();
include '../config/db.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../user/login.php");
    exit;
}

// Ambil kategori yang dipilih dari URL (jika ada)
$selected_kategori = isset($_GET['kategori']) ? intval($_GET['kategori']) : 0;

// Ambil semua kategori untuk dropdown
$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku - Admin BookStore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .card-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        .form-control:focus, .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .btn-custom {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
        }
        .btn-custom:hover {
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
            color: white;
        }
        .image-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .image-upload-area:hover {
            border-color: #28a745;
            background-color: #f8fff9;
        }
        .image-upload-area.dragover {
            border-color: #28a745;
            background-color: #e8f5e9;
        }
        .preview-image {
            max-width: 200px;
            max-height: 250px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .feature-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="text-success fw-bold">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Buku Baru
                    </h1>
                    <p class="text-muted mb-0">Tambahkan buku baru ke dalam koleksi toko</p>
                </div>
                <div>
                    <a href="buku.php" class="btn btn-outline-primary me-2">
                        <i class="bi bi-list"></i> Daftar Buku
                    </a>
                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Dashboard
                    </a>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-image text-success" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Upload Gambar</h6>
                            <small class="text-muted">Gunakan gambar dengan resolusi tinggi untuk hasil terbaik</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Validasi Otomatis</h6>
                            <small class="text-muted">Form akan memvalidasi semua input secara otomatis</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Data Aman</h6>
                            <small class="text-muted">Semua data akan tersimpan dengan aman di database</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Tambah Buku -->
            <div class="card border-0 shadow">
                <div class="card-header text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-book-fill"></i> Form Data Buku
                    </h5>
                </div>
                <div class="card-body p-4">
                    
                    <form method="POST" action="../proses/proses_buku.php" enctype="multipart/form-data" id="addBookForm">
                        
                        <div class="row g-4">
                            
                            <!-- Upload Gambar -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-image"></i> Gambar Cover Buku
                                </label>
                                
                                <!-- Upload Area -->
                                <div class="image-upload-area" onclick="document.getElementById('gambar').click()">
                                    <div id="upload-placeholder">
                                        <i class="bi bi-cloud-upload text-success" style="font-size: 3rem;"></i>
                                        <div class="mt-3">
                                            <h6>Klik untuk pilih gambar</h6>
                                            <p class="text-muted mb-0">atau drag & drop file di sini</p>
                                        </div>
                                        <div class="text-muted small mt-2">
                                            Format: JPG, JPEG, PNG, GIF<br>
                                            Maksimal: 5MB
                                        </div>
                                    </div>
                                    <div id="image-preview" style="display: none;">
                                        <img id="preview-img" class="preview-image" alt="Preview">
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <input type="file" 
                                       name="gambar" 
                                       id="gambar" 
                                       class="d-none" 
                                       accept="image/*" 
                                       required
                                       onchange="previewImage(this)">
                                
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i> 
                                    Gambar akan di-resize otomatis untuk optimasi
                                </div>
                            </div>

                            <!-- Data Buku -->
                            <div class="col-md-8">
                                
                                <!-- Judul dan Penulis -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-book"></i> Judul Buku <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               name="judul" 
                                               class="form-control" 
                                               required 
                                               placeholder="Masukkan judul buku"
                                               maxlength="255">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-person"></i> Penulis <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               name="penulis" 
                                               class="form-control" 
                                               required 
                                               placeholder="Nama penulis"
                                               maxlength="255">
                                    </div>
                                </div>

                                <!-- Harga dan Stok -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-currency-dollar"></i> Harga <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" 
                                                   name="harga" 
                                                   class="form-control" 
                                                   required 
                                                   min="0"
                                                   placeholder="0"
                                                   onkeyup="formatRupiah(this)">
                                        </div>
                                        <div class="form-text">Harga dalam rupiah</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-box"></i> Stok Awal <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               name="stok" 
                                               class="form-control" 
                                               required 
                                               min="0"
                                               placeholder="0">
                                        <div class="form-text">Jumlah stok yang tersedia</div>
                                    </div>
                                </div>

                                <!-- Kategori -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-tag"></i> Kategori <span class="text-danger">*</span>
                                    </label>
                                    <select name="kategori" class="form-select" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
                                            <option value="<?= $k['id'] ?>" <?= $k['id'] == $selected_kategori ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($k['nama_kategori']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                    <div class="form-text">
                                        <a href="kategori.php" class="text-decoration-none">
                                            <i class="bi bi-plus-circle"></i> Kelola kategori
                                        </a>
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-text-paragraph"></i> Deskripsi <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="deskripsi" 
                                              class="form-control" 
                                              rows="4" 
                                              required
                                              placeholder="Masukkan deskripsi lengkap tentang buku..."
                                              maxlength="1000"></textarea>
                                    <div class="form-text">
                                        <span id="desc-count">0</span>/1000 karakter
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i> 
                                            Semua field bertanda <span class="text-danger">*</span> wajib diisi
                                        </small>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-secondary me-2" onclick="resetForm()">
                                            <i class="bi bi-arrow-clockwise"></i> Reset
                                        </button>
                                        <button type="button" class="btn btn-outline-primary me-2" onclick="previewData()">
                                            <i class="bi bi-eye"></i> Preview
                                        </button>
                                        <button type="submit" name="tambah" class="btn btn-custom">
                                            <i class="bi bi-check-lg"></i> Simpan Buku
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <i class="bi bi-list-ul text-primary" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Lihat Daftar Buku</h6>
                            <p class="text-muted small">Kelola semua buku yang sudah ditambahkan</p>
                            <a href="buku.php" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-arrow-right"></i> Ke Daftar Buku
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-info">
                        <div class="card-body text-center">
                            <i class="bi bi-tag text-info" style="font-size: 2rem;"></i>
                            <h6 class="mt-2">Kelola Kategori</h6>
                            <p class="text-muted small">Tambah atau edit kategori buku</p>
                            <a href="kategori.php" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-arrow-right"></i> Ke Kategori
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Preview gambar
function previewImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validasi ukuran file (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maximum 5MB.');
            input.value = '';
            return;
        }
        
        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Tipe file tidak diizinkan! Gunakan JPG, JPEG, PNG, atau GIF.');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('upload-placeholder').style.display = 'none';
            document.getElementById('image-preview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

// Hapus gambar
function removeImage() {
    document.getElementById('gambar').value = '';
    document.getElementById('upload-placeholder').style.display = 'block';
    document.getElementById('image-preview').style.display = 'none';
}

// Drag and drop functionality
const uploadArea = document.querySelector('.image-upload-area');

uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('gambar').files = files;
        previewImage(document.getElementById('gambar'));
    }
});

// Format rupiah
function formatRupiah(input) {
    // Remove non-digits
    let value = input.value.replace(/[^0-9]/g, '');
    
    // Format with dots
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    
    // Update input value
    input.value = value.replace(/\./g, '');
}

// Character counter for description
document.querySelector('textarea[name="deskripsi"]').addEventListener('input', function() {
    const counter = document.getElementById('desc-count');
    counter.textContent = this.value.length;
    
    if (this.value.length > 900) {
        counter.style.color = '#dc3545';
    } else {
        counter.style.color = '#6c757d';
    }
});

// Preview data
function previewData() {
    const form = document.getElementById('addBookForm');
    const formData = new FormData(form);
    
    let preview = 'PREVIEW DATA BUKU:\n\n';
    preview += 'Judul: ' + formData.get('judul') + '\n';
    preview += 'Penulis: ' + formData.get('penulis') + '\n';
    preview += 'Harga: Rp ' + Number(formData.get('harga')).toLocaleString('id-ID') + '\n';
    preview += 'Stok: ' + formData.get('stok') + '\n';
    
    const kategoriSelect = form.querySelector('select[name="kategori"]');
    preview += 'Kategori: ' + (kategoriSelect.selectedIndex > 0 ? kategoriSelect.options[kategoriSelect.selectedIndex].text : 'Belum dipilih') + '\n';
    
    const deskripsi = formData.get('deskripsi');
    preview += 'Deskripsi: ' + (deskripsi ? deskripsi.substring(0, 100) + '...' : 'Belum diisi') + '\n';
    
    const gambarFile = formData.get('gambar');
    preview += 'Gambar: ' + (gambarFile && gambarFile.name ? gambarFile.name : 'Belum dipilih') + '\n';
    
    alert(preview);
}

// Reset form
function resetForm() {
    if (confirm('Yakin ingin mengosongkan semua field?')) {
        document.getElementById('addBookForm').reset();
        removeImage();
        document.getElementById('desc-count').textContent = '0';
    }
}

// Form validation
document.getElementById('addBookForm').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let allValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            allValid = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!allValid) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi!');
        return;
    }
    
    if (!confirm('Yakin ingin menyimpan buku ini?')) {
        e.preventDefault();
    }
});
</script>

</body>
</html>
