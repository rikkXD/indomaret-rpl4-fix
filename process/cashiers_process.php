<?php
require_once __DIR__ . '/../config/config.php'; require_once __DIR__ . '/../includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('pages/cashiers/list.php');
try {
    $action = $_POST['action'] ?? '';
    if ($action === 'create' || $action === 'update') {
        $name = trim((string) ($_POST['nama_kasir'] ?? '')); $phone = trim((string) ($_POST['no_telp'] ?? '')); $address = trim((string) ($_POST['alamat'] ?? ''));
        if ($name === '' || mb_strlen($name) > 50 || $phone === '' || mb_strlen($phone) > 20 || $address === '') throw new InvalidArgumentException('Nama, nomor telepon, dan alamat kasir wajib diisi dengan panjang yang valid.');
        if ($action === 'create') { $stmt = mysqli_prepare($conn, 'INSERT INTO tabel_kasir (nama_kasir, no_telp, alamat) VALUES (?, ?, ?)'); mysqli_stmt_bind_param($stmt, 'sss', $name, $phone, $address); mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt); set_flash('success', 'Kasir berhasil ditambahkan.'); }
        else { $id = valid_positive_int($_POST['id_kasir'] ?? null); if ($id === null) throw new InvalidArgumentException('ID kasir tidak valid.'); $stmt = mysqli_prepare($conn, 'UPDATE tabel_kasir SET nama_kasir = ?, no_telp = ?, alamat = ? WHERE id_kasir = ?'); mysqli_stmt_bind_param($stmt, 'sssi', $name, $phone, $address, $id); mysqli_stmt_execute($stmt); $changed = mysqli_stmt_affected_rows($stmt); mysqli_stmt_close($stmt); set_flash('success', $changed > 0 ? 'Kasir berhasil diperbarui.' : 'Tidak ada perubahan pada kasir.'); }
    } elseif ($action === 'delete') { $id = valid_positive_int($_POST['id_kasir'] ?? null); if ($id === null) throw new InvalidArgumentException('ID kasir tidak valid.'); $stmt = mysqli_prepare($conn, 'DELETE FROM tabel_kasir WHERE id_kasir = ?'); mysqli_stmt_bind_param($stmt, 'i', $id); mysqli_stmt_execute($stmt); $changed = mysqli_stmt_affected_rows($stmt); mysqli_stmt_close($stmt); set_flash($changed > 0 ? 'success' : 'error', $changed > 0 ? 'Kasir berhasil dihapus.' : 'Kasir tidak ditemukan.'); }
    else throw new InvalidArgumentException('Permintaan tidak dikenali.');
} catch (InvalidArgumentException $exception) { set_flash('error', $exception->getMessage());
} catch (mysqli_sql_exception $exception) { error_log('Proses kasir gagal: ' . $exception->getMessage()); set_flash('error', (int) $exception->getCode() === 1451 ? 'Kasir tidak dapat dihapus karena sudah dipakai pada transaksi.' : 'Data kasir tidak dapat disimpan saat ini.'); }
redirect('pages/cashiers/list.php');
