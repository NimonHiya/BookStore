<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['selesai'])) {
    $pesanan_id = (int)$_GET['selesai'];
    $user_id = $_SESSION['user_id'];
    
    // Validasi apakah pesanan milik user yang login dan statusnya 'diproses'
    $check_pesanan = mysqli_query($conn, "
        SELECT * FROM pesanan 
        WHERE id = $pesanan_id 
        AND user_id = $user_id 
        AND status = 'diproses'
    ");
    
    if (mysqli_num_rows($check_pesanan) == 0) {
        echo "<script>
            alert('Pesanan tidak ditemukan, bukan milik Anda, atau tidak dalam status diproses!');
            window.location.href = 'pesanansaya.php';
        </script>";
        exit;
    }
    
    // Update status pesanan menjadi 'selesai'
    $update_status = mysqli_query($conn, "
        UPDATE pesanan 
        SET status = 'selesai' 
        WHERE id = $pesanan_id
    ");
    
    if ($update_status) {
        echo "<script>
            alert('Pesanan berhasil diselesaikan!\\nTerima kasih telah berbelanja di BookStore.');
            window.location.href = '../user/index.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menyelesaikan pesanan. Silakan coba lagi.');
            window.location.href = 'pesanansaya.php';
        </script>";
    }
} else {
    header("Location: pesanansaya.php");
    exit;
}
?>
