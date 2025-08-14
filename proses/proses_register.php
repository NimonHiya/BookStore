<?php
// Koneksi ke database
include '../config/db.php'; // Pastikan file db.php berisi koneksi $conn

if (isset($_POST['register'])) {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password
    $role = 'user'; // Default peran saat registrasi

    // Cek apakah email sudah digunakan
    $cek = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $cek->bind_param("s", $email);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        // Email sudah terdaftar
        echo "<script>alert('Email sudah digunakan. Silakan gunakan email lain.'); window.location.href='../user/register.php';</script>";
    } else {
        // Simpan data user baru
        $stmt = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $email, $password, $role);

        if ($stmt->execute()) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='../user/login.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat registrasi.'); window.location.href='../user/register.php';</script>";
        }

        $stmt->close();
    }

    $cek->close();
    $conn->close();
} else {
    // Jika tidak ada request POST
    header("Location: ../user/register.php");
    exit;
}
?>
