<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Proses update status ke 'diproses'
if (isset($_GET['kirim'])) {
    $id = intval($_GET['kirim']);
    $cek = mysqli_query($conn, "SELECT status FROM pesanan WHERE id=$id");
    $data = mysqli_fetch_assoc($cek);

    if ($data && $data['status'] == 'dibayar') {
        mysqli_query($conn, "UPDATE pesanan SET status='diproses' WHERE id=$id");
    }

    header("Location: pesanan.php");
    exit;
}

// Proses pembatalan pesanan
if (isset($_GET['batal'])) {
    $id = intval($_GET['batal']);
    $cek = mysqli_query($conn, "SELECT status FROM pesanan WHERE id=$id");
    $data = mysqli_fetch_assoc($cek);

    if ($data && ($data['status'] == 'dibayar' || $data['status'] == 'belum bayar')) {
        mysqli_query($conn, "DELETE FROM pesanan WHERE id=$id");
    }

    header("Location: pesanan.php");
    exit;
}

// Ambil semua data pesanan
$pesanan = mysqli_query($conn, "
    SELECT pesanan.*, users.nama 
    FROM pesanan 
    JOIN users ON pesanan.user_id = users.id 
    ORDER BY pesanan.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            display: inline-block;
            text-transform: capitalize;
        }
        .belumbayar {
            background-color: #fcd34d;
            color: #92400e;
        }
        .diproses {
            background-color: #34d399;
            color: #065f46;
        }
        .dibayar {
            background-color: #60a5fa;
            color: #1e40af;
        }
        .selesai {
            background-color: #a3e635;
            color: #365314;
        }
        .batal {
            background-color: #f87171;
            color: #991b1b;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary"><i class="bi bi-box-seam"></i> Data Pesanan Buku</h1>
        <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Pemesan</th>
                        <th>Tanggal Pesan</th>
                        <th>Status & Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $no = 1; 
                while ($p = mysqli_fetch_assoc($pesanan)) {
                    $status_class = str_replace(' ', '', strtolower($p['status']));
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($p['nama']) ?></td>
                        <td><?= htmlspecialchars($p['tanggal_pesan']) ?></td>
                        <td>
                            <span class="status <?= $status_class ?>"><?= htmlspecialchars($p['status']) ?></span>
                            <div class="mt-2">
                                <!-- Tombol Lihat Struk untuk pesanan yang sudah dibayar atau lebih -->
                                <?php if (in_array(strtolower($p['status']), ['dibayar', 'diproses', 'selesai'])) { ?>
                                    <a class="btn btn-sm btn-info mb-1" href="struk.php?id=<?= $p['id'] ?>">
                                        <i class="bi bi-receipt"></i> Lihat Struk
                                    </a>
                                    <br>
                                <?php } ?>
                                
                                <?php if ($p['status'] == 'dibayar') { ?>
                                    <a class="btn btn-sm btn-success" href="pesanan.php?kirim=<?= $p['id'] ?>">
                                        <i class="bi bi-truck"></i> Kirim
                                    </a>
                                    <a class="btn btn-sm btn-danger" href="pesanan.php?batal=<?= $p['id'] ?>" onclick="return confirm('Yakin ingin batalkan dan hapus pesanan ini?')">
                                        <i class="bi bi-x-circle"></i> Batalkan
                                    </a>
                                <?php } elseif ($p['status'] == 'belum bayar') { ?>
                                    <a class="btn btn-sm btn-danger" href="pesanan.php?batal=<?= $p['id'] ?>" onclick="return confirm('Yakin ingin batalkan dan hapus pesanan ini?')">
                                        <i class="bi bi-x-circle"></i> Batalkan
                                    </a>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
