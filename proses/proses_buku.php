<?php
include '../config/db.php';

// Tambah Buku
if (isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];

    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    
    // Validasi file gambar
    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
    $file_ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
    
    if (in_array($file_ext, $allowed_ext)) {
        // Rename file untuk menghindari duplikasi
        $new_name = time() . '_' . $gambar;
        if (move_uploaded_file($tmp, "../assets/img/" . $new_name)) {
            mysqli_query($conn, "INSERT INTO buku (judul, penulis, harga, stok, kategori_id, deskripsi, gambar) 
            VALUES ('$judul', '$penulis', '$harga', '$stok', '$kategori', '$deskripsi', '$new_name')");
        }
    }
    header("Location: ../admin/buku.php");
}

// Update Buku
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];

    // Cek jika ada gambar baru
    if ($_FILES['gambar']['name']) {
        // Ambil gambar lama untuk dihapus
        $query_old = mysqli_query($conn, "SELECT gambar FROM buku WHERE id=$id");
        $old_data = mysqli_fetch_assoc($query_old);
        
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        
        // Validasi file gambar
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
        $file_ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
        
        if (in_array($file_ext, $allowed_ext)) {
            // Rename file untuk menghindari duplikasi
            $new_name = time() . '_' . $gambar;
            if (move_uploaded_file($tmp, "../assets/img/" . $new_name)) {
                // Hapus gambar lama jika ada
                if (!empty($old_data['gambar']) && file_exists("../assets/img/" . $old_data['gambar'])) {
                    unlink("../assets/img/" . $old_data['gambar']);
                }
                mysqli_query($conn, "UPDATE buku SET judul='$judul', penulis='$penulis', harga='$harga', stok='$stok', kategori_id='$kategori', deskripsi='$deskripsi', gambar='$new_name' WHERE id=$id");
            }
        }
    } else {
        mysqli_query($conn, "UPDATE buku SET judul='$judul', penulis='$penulis', harga='$harga', stok='$stok', kategori_id='$kategori', deskripsi='$deskripsi' WHERE id=$id");
    }
    header("Location: ../admin/buku.php");
}

// Hapus Buku
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus']; // Sanitasi input
    
    // Cek stok buku sebelum dihapus
    $query = mysqli_query($conn, "SELECT stok, gambar, judul FROM buku WHERE id=$id");
    $data = mysqli_fetch_assoc($query);
    
    // Cek apakah buku ada
    if (!$data) {
        echo "<script>
            alert('Buku tidak ditemukan!');
            window.location.href = '../admin/buku.php';
        </script>";
        exit();
    }
    
    // Validasi stok harus 0 untuk bisa dihapus
    if ($data['stok'] > 0) {
        echo "<script>
            alert('Buku \"" . addslashes($data['judul']) . "\" tidak dapat dihapus!\\nStok masih tersedia (" . $data['stok'] . "). Kurangi stok menjadi 0 terlebih dahulu.');
            window.location.href = '../admin/buku.php';
        </script>";
        exit();
    }
    
    // Hapus file gambar jika ada
    if (!empty($data['gambar']) && file_exists("../assets/img/" . $data['gambar'])) {
        unlink("../assets/img/" . $data['gambar']);
    }
    
    // Hapus data dari database
    $result = mysqli_query($conn, "DELETE FROM buku WHERE id=$id");
    
    if ($result) {
        echo "<script>
            alert('Buku \"" . addslashes($data['judul']) . "\" berhasil dihapus!');
            window.location.href = '../admin/buku.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus buku. Silakan coba lagi.');
            window.location.href = '../admin/buku.php';
        </script>";
    }
}
?>
