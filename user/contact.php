<?php
session_start();
include '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    echo "Akses ditolak. Silakan <a href='login.php'>login</a> terlebih dahulu.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kontak Admin - BookStore</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(120deg, #dbeafe, #f8fafc);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 520px;
            margin: 80px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 12px 28px rgba(0,0,0,0.08);
        }

        h1 {
            text-align: center;
            color: #1e3a8a;
            font-size: 26px;
            margin-bottom: 30px;
        }

        input, textarea {
            width: 100%;
            padding: 14px;
            margin-top: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 15px;
            transition: border 0.2s;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        button, .btn-link {
            width: 100%;
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 14px;
            margin-top: 22px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            display: block;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        button:hover, .btn-link:hover {
            background-color: #2563eb;
        }

        .btn-link {
            margin-top: 12px;
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

        .emoji {
            margin-right: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hubungi Admin</h1>
        <form action="../proses/proses_contact.php" method="POST">
            <input type="text" name="nama" placeholder="👤 Nama Anda" required>
            <input type="email" name="email" placeholder="📧 Email Anda" required>
            <textarea name="pesan" rows="5" placeholder="📝 Tulis pesan Anda di sini..." required></textarea>
            <button type="submit">📨 Kirim Pesan</button>
        </form>

        <a href="lihat_balasan.php" class="btn-link">📬 Lihat Balasan dari Admin</a>

        <div class="back-link">
            <a href="index.php">← Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
