<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') exit;

if (!isset($_GET['id'])) {
    echo "ID pesan tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);
$data = mysqli_query($conn, "SELECT k.*, u.nama, u.email FROM kontak k 
                             JOIN users u ON k.user_id = u.id 
                             WHERE k.id = $id");
$pesan = mysqli_fetch_assoc($data);

if (!$pesan) {
    echo "Pesan tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Balas Pesan - BookStore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-sm mx-auto" style="max-width: 650px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Balas Pesan Pengguna</h3>

            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Nama:</strong> <?= htmlspecialchars($pesan['nama']) ?></li>
                <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($pesan['email']) ?></li>
                <li class="list-group-item"><strong>Isi Pesan:</strong><br><?= nl2br(htmlspecialchars($pesan['pesan'])) ?></li>
            </ul>

            <form action="proses_balas.php" method="POST">
                <input type="hidden" name="email" value="<?= $pesan['email'] ?>">
                <input type="hidden" name="nama" value="<?= $pesan['nama'] ?>">

                <div class="mb-3">
                    <label for="balasan" class="form-label fw-semibold">Balasan:</label>
                    <textarea name="balasan" id="balasan" class="form-control" placeholder="Tulis balasan Anda di sini..." rows="5" required></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="pesan.php" class="btn btn-secondary">
                        ← Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Kirim Balasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
