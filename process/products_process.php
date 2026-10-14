<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('pages/products/list.php'); }
$action = $_POST['action'] ?? '';
try {
    if ($action === 'create' || $action === 'update') {
        $name = trim((string) ($_POST['nama_produk'] ?? '')); $price = valid_nonnegative_int($_POST['harga'] ?? null); $discount = valid_nonnegative_int($_POST['diskon'] ?? null);
        if ($name === '' || mb_strlen($name) > 50 || $price === null || $discount === null || $discount > $price) throw new InvalidArgumentException('Nama produk wajib diisi; harga dan diskon harus bilangan bulat valid, dengan diskon tidak melebihi harga.');
        if ($action === 'create') { $stmt = mysqli_prepare($conn, 'INSERT INTO tabel_produk (nama_produk, harga, diskon) VALUES (?, ?, ?)'); mysqli_stmt_bind_param($stmt, 'sii', $name, $price, $discount); mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt); set_flash('success', 'Produk berhasil ditambahkan.'); }
        else { $id = valid_positive_int($_POST['id_produk'] ?? null); if ($id === null) throw new InvalidArgumentException('ID produk tidak valid.'); $stmt = mysqli_prepare($conn, 'UPDATE tabel_produk SET nama_produk = ?, harga = ?, diskon = ? WHERE id_produk = ?'); mysqli_stmt_bind_param($stmt, 'siii', $name, $price, $discount, $id); mysqli_stmt_execute($stmt); $changed = mysqli_stmt_affected_rows($stmt); mysqli_stmt_close($stmt); set_flash('success', $changed > 0 ? 'Produk berhasil diperbarui.' : 'Tidak ada perubahan pada produk.'); }
    } elseif ($action === 'delete') {
        $id = valid_positive_int($_POST['id_produk'] ?? null); if ($id === null) throw new InvalidArgumentException('ID produk tidak valid.'); $stmt = mysqli_prepare($conn, 'DELETE FROM tabel_produk WHERE id_produk = ?'); mysqli_stmt_bind_param($stmt, 'i', $id); mysqli_stmt_execute($stmt); $changed = mysqli_stmt_affected_rows($stmt); mysqli_stmt_close($stmt); set_flash($changed > 0 ? 'success' : 'error', $changed > 0 ? 'Produk berhasil dihapus.' : 'Produk tidak ditemukan.');
    } else { throw new InvalidArgumentException('Permintaan tidak dikenali.'); }
} catch (InvalidArgumentException $exception) { set_flash('error', $exception->getMessage());
} catch (mysqli_sql_exception $exception) { error_log('Proses produk gagal: ' . $exception->getMessage()); set_flash('error', (int) $exception->getCode() === 1451 ? 'Produk tidak dapat dihapus karena sudah dipakai pada transaksi.' : 'Data produk tidak dapat disimpan saat ini.'); }
redirect('pages/products/list.php');
