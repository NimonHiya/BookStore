<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    echo "Akses ditolak. Silakan <a href='login.php'>login</a> terlebih dahulu.";
    exit();
}

// Ambil email user yang login
$user_id = $_SESSION['user_id'];
$query_email = mysqli_query($conn, "SELECT email FROM users WHERE id = '$user_id'");
$data_user = mysqli_fetch_assoc($query_email);
$email_user = $data_user['email'];

// Ambil balasan berdasarkan email
$balasan = mysqli_query($conn, "SELECT * FROM balasan WHERE email = '$email_user' ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Balasan Admin - BookStore</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #1e40af;
            margin-bottom: 30px;
        }

        .balasan {
            background: #f9fafb;
            border: 1px solid #d1d5db;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .balasan p {
            margin: 8px 0;
            line-height: 1.6;
        }

        .tanggal {
            font-size: 13px;
            color: #6b7280;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #1e40af;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Balasan dari Admin</h1>

    <?php if (mysqli_num_rows($balasan) > 0): ?>
        <?php while ($b = mysqli_fetch_assoc($balasan)): ?>
            <div class="balasan">
                <p><strong>Admin:</strong></p>
                <p><?= nl2br(htmlspecialchars($b['pesan_balasan'])) ?></p>
                <p class="tanggal">Dikirim pada: <?= $b['tanggal'] ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Tidak ada balasan dari admin saat ini.</p>
    <?php endif; ?>

    <div class="back-link">
        <a href="index.php">← Kembali ke Beranda</a>
    </div>
</div>
</body>
</html>
