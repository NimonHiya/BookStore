<?php
session_start();
include '../config/db.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../user/login.php");
    exit;
}

// Ambil ID buku yang akan diedit
$buku_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($buku_id == 0) {
    header("Location: buku.php");
    exit;
}

// Ambil data buku
$buku_query = mysqli_query($conn, "
    SELECT buku.*, kategori.nama_kategori 
    FROM buku 
    LEFT JOIN kategori ON buku.kategori_id = kategori.id 
    WHERE buku.id = $buku_id
");

if (mysqli_num_rows($buku_query) == 0) {
    echo "<script>
        alert('Buku tidak ditemukan!');
        window.location.href = 'buku.php';
    </script>";
    exit;
}

$buku_data = mysqli_fetch_assoc($buku_query);

// Ambil semua kategori untuk dropdown
$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku - Admin BookStore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .preview-image {
            max-width: 200px;
            max-height: 250px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-custom:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
            color: white;
        }
        .image-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .image-upload-area:hover {
            border-color: #667eea;
            background-color: #f8f9ff;
        }
        .image-upload-area.dragover {
            border-color: #667eea;
            background-color: #f0f2ff;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-primary fw-bold">
                    <i class="bi bi-pencil-square"></i> Edit Buku
                </h1>
                <a href="buku.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Buku
                </a>
            </div>

            <!-- Info Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="text-muted mb-1">Mengedit Buku:</h6>
                            <h5 class="mb-0 text-primary"><?= htmlspecialchars($buku_data['judul']) ?></h5>
                            <small class="text-muted">oleh <?= htmlspecialchars($buku_data['penulis']) ?></small>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge bg-info">ID: <?= $buku_data['id'] ?></span>
                            <br>
                            <span class="badge bg-success mt-1">Stok: <?= $buku_data['stok'] ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Edit -->
            <div class="card border-0 shadow">
                <div class="card-header text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-book"></i> Form Edit Data Buku
                    </h5>
                </div>
                <div class="card-body p-4">
                    
                    <form method="POST" action="../proses/proses_buku.php" enctype="multipart/form-data" id="editForm">
                        <input type="hidden" name="id" value="<?= $buku_data['id'] ?>">

                        <div class="row g-4">
                            
                            <!-- Gambar Buku -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-image"></i> Gambar Buku
                                </label>
                                
                                <!-- Preview Gambar Saat Ini -->
                                <div class="mb-3">
                                    <?php if (!empty($buku_data['gambar']) && file_exists("../assets/img/" . $buku_data['gambar'])): ?>
                                        <div class="text-center">
                                            <img src="../assets/img/<?= htmlspecialchars($buku_data['gambar']) ?>" 
                                                 class="preview-image" 
                                                 id="currentImage"
                                                 alt="Cover Buku">
                                            <div class="mt-2">
                                                <small class="text-muted">Gambar saat ini</small>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center text-muted">
                                            <i class="bi bi-image" style="font-size: 3rem;"></i>
                                            <div>Tidak ada gambar</div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Upload Area -->
                                <div class="image-upload-area" onclick="document.getElementById('gambar').click()">
                                    <i class="bi bi-cloud-upload text-primary" style="font-size: 2rem;"></i>
                                    <div class="mt-2">
                                        <strong>Klik untuk pilih gambar baru</strong>
                                        <div class="text-muted small">atau drag & drop file di sini</div>
                                    </div>
                                    <div class="text-muted small mt-2">
                                        Format: JPG, JPEG, PNG, GIF (Max: 5MB)
                                    </div>
                                </div>
                                
                                <input type="file" 
                                       name="gambar" 
                                       id="gambar" 
                                       class="form-control d-none" 
                                       accept="image/*"
                                       onchange="previewNewImage(this)">
                                
                                <!-- Preview Gambar Baru -->
                                <div id="newImagePreview" class="mt-3" style="display: none;">
                                    <div class="text-center">
                                        <img id="newImage" class="preview-image" alt="Preview">
                                        <div class="mt-2">
                                            <small class="text-success">Preview gambar baru</small>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="cancelNewImage()">
                                                <i class="bi bi-x"></i> Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Buku -->
                            <div class="col-md-8">
                                
                                <!-- Judul dan Penulis -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-book-fill"></i> Judul Buku
                                        </label>
                                        <input type="text" 
                                               name="judul" 
                                               class="form-control" 
                                               required 
                                               value="<?= htmlspecialchars($buku_data['judul']) ?>"
                                               placeholder="Masukkan judul buku">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-person-fill"></i> Penulis
                                        </label>
                                        <input type="text" 
                                               name="penulis" 
                                               class="form-control" 
                                               required 
                                               value="<?= htmlspecialchars($buku_data['penulis']) ?>"
                                               placeholder="Nama penulis">
                                    </div>
                                </div>

                                <!-- Harga dan Stok -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-currency-dollar"></i> Harga (Rp)
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" 
                                                   name="harga" 
                                                   class="form-control" 
                                                   required 
                                                   min="0"
                                                   value="<?= $buku_data['harga'] ?>"
                                                   placeholder="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="bi bi-box"></i> Stok
                                        </label>
                                        <input type="number" 
                                               name="stok" 
                                               class="form-control" 
                                               required 
                                               min="0"
                                               value="<?= $buku_data['stok'] ?>"
                                               placeholder="0">
                                    </div>
                                </div>

                                <!-- Kategori -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-tag-fill"></i> Kategori
                                    </label>
                                    <select name="kategori" class="form-select" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
                                            <option value="<?= $k['id'] ?>" 
                                                    <?= ($buku_data['kategori_id'] == $k['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($k['nama_kategori']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <!-- Deskripsi -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-text-paragraph"></i> Deskripsi
                                    </label>
                                    <textarea name="deskripsi" 
                                              class="form-control" 
                                              rows="4" 
                                              placeholder="Masukkan deskripsi buku..."><?= htmlspecialchars($buku_data['deskripsi']) ?></textarea>
                                </div>

                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="buku.php" class="btn btn-secondary">
                                            <i class="bi bi-x-circle"></i> Batal
                                        </a>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary me-2" onclick="previewChanges()">
                                            <i class="bi bi-eye"></i> Preview
                                        </button>
                                        <button type="submit" name="update" class="btn btn-custom">
                                            <i class="bi bi-check-lg"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Warning untuk Stok -->
            <?php if ($buku_data['stok'] == 0): ?>
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Stok buku ini adalah 0. Buku dapat dihapus jika diperlukan.
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Preview gambar baru
function previewNewImage(input) {
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
            document.getElementById('newImage').src = e.target.result;
            document.getElementById('newImagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

// Batalkan gambar baru
function cancelNewImage() {
    document.getElementById('gambar').value = '';
    document.getElementById('newImagePreview').style.display = 'none';
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
        previewNewImage(document.getElementById('gambar'));
    }
});

// Preview perubahan
function previewChanges() {
    const form = document.getElementById('editForm');
    const formData = new FormData(form);
    
    let preview = 'PREVIEW PERUBAHAN:\n\n';
    preview += 'Judul: ' + formData.get('judul') + '\n';
    preview += 'Penulis: ' + formData.get('penulis') + '\n';
    preview += 'Harga: Rp ' + Number(formData.get('harga')).toLocaleString('id-ID') + '\n';
    preview += 'Stok: ' + formData.get('stok') + '\n';
    
    const kategoriSelect = form.querySelector('select[name="kategori"]');
    preview += 'Kategori: ' + kategoriSelect.options[kategoriSelect.selectedIndex].text + '\n';
    
    const deskripsi = formData.get('deskripsi');
    preview += 'Deskripsi: ' + (deskripsi ? deskripsi.substring(0, 100) + '...' : 'Tidak ada') + '\n';
    
    const gambarFile = formData.get('gambar');
    preview += 'Gambar: ' + (gambarFile && gambarFile.name ? 'File baru: ' + gambarFile.name : 'Tidak diubah') + '\n';
    
    alert(preview);
}

// Konfirmasi sebelum submit
document.getElementById('editForm').addEventListener('submit', function(e) {
    if (!confirm('Yakin ingin menyimpan perubahan data buku ini?')) {
        e.preventDefault();
    }
});
</script>

</body>
</html>
