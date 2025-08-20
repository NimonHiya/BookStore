-- Update Database BookStore untuk Foreign Key Constraints
-- Jalankan script ini di phpMyAdmin atau MySQL console

-- 1. Backup data terlebih dahulu sebelum menjalankan script ini
-- 2. Pastikan tidak ada data yang rusak/inconsistent

-- Hapus foreign key yang mungkin sudah ada
ALTER TABLE detail_pesanan DROP FOREIGN KEY IF EXISTS fk_detail_buku;
ALTER TABLE detail_pesanan DROP FOREIGN KEY IF EXISTS fk_detail_pesanan;

-- Tambahkan foreign key constraint dengan CASCADE DELETE
-- Ini akan otomatis menghapus detail_pesanan ketika buku dihapus
ALTER TABLE detail_pesanan 
ADD CONSTRAINT fk_detail_buku 
FOREIGN KEY (buku_id) REFERENCES buku(id) 
ON DELETE CASCADE ON UPDATE CASCADE;

-- Tambahkan foreign key untuk pesanan juga
ALTER TABLE detail_pesanan 
ADD CONSTRAINT fk_detail_pesanan 
FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) 
ON DELETE CASCADE ON UPDATE CASCADE;

-- Tampilkan struktur tabel untuk verifikasi
SHOW CREATE TABLE detail_pesanan;

-- Query untuk cek constraint yang aktif
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME,
    DELETE_RULE,
    UPDATE_RULE
FROM 
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE 
    REFERENCED_TABLE_SCHEMA = 'bookstore' 
    AND TABLE_NAME = 'detail_pesanan';
