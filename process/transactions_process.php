<?php
require_once __DIR__ . '/../config/config.php'; require_once __DIR__ . '/../includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'create') redirect('pages/transactions/add.php');
$transactionStarted = false;
try {
    $cashierId = valid_positive_int($_POST['id_kasir'] ?? null); $productId = valid_positive_int($_POST['id_produk'] ?? null); $quantity = valid_positive_int($_POST['kuantitas'] ?? null);
    $donationInput = $_POST['id_donasi'] ?? ''; $donationId = $donationInput === '' ? null : valid_positive_int($donationInput); $paymentMethod = $_POST['metode_pembayaran'] ?? ''; $cashReceived = valid_nonnegative_int($_POST['uang_bayar'] ?? null);
    if ($cashierId === null || $productId === null || $quantity === null || ($donationInput !== '' && $donationId === null) || !in_array($paymentMethod, ['qris', 'tunai'], true) || $cashReceived === null) throw new InvalidArgumentException('Data transaksi tidak lengkap atau tidak valid.');
    mysqli_begin_transaction($conn); $transactionStarted = true;
    $stmt = mysqli_prepare($conn, 'SELECT nama_produk, harga, diskon FROM tabel_produk WHERE id_produk = ?'); mysqli_stmt_bind_param($stmt, 'i', $productId); mysqli_stmt_execute($stmt); $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); mysqli_stmt_close($stmt);
    if ($product === null) throw new InvalidArgumentException('Produk tidak ditemukan.');
    $stmt = mysqli_prepare($conn, 'SELECT id_kasir FROM tabel_kasir WHERE id_kasir = ?'); mysqli_stmt_bind_param($stmt, 'i', $cashierId); mysqli_stmt_execute($stmt); $cashier = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); mysqli_stmt_close($stmt);
    if ($cashier === null) throw new InvalidArgumentException('Kasir tidak ditemukan.');
    $donationAmount = 0;
    if ($donationId !== null) { $stmt = mysqli_prepare($conn, 'SELECT harga_donasi FROM tabel_donasi WHERE id_donasi = ?'); mysqli_stmt_bind_param($stmt, 'i', $donationId); mysqli_stmt_execute($stmt); $donation = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); mysqli_stmt_close($stmt); if ($donation === null) throw new InvalidArgumentException('Pilihan donasi tidak ditemukan.'); $donationAmount = (int) $donation['harga_donasi']; }
    $unitPrice = (int) $product['harga']; $unitDiscount = (int) $product['diskon']; $lineTotal = ($unitPrice - $unitDiscount) * $quantity; $total = $lineTotal + $donationAmount;
    if ($paymentMethod === 'qris') { $qris = (float) $total; $cash = 0; $change = 0; } else { if ($cashReceived < $total) throw new InvalidArgumentException('Uang tunai kurang dari total pembayaran.'); $qris = 0.0; $cash = $cashReceived; $change = $cashReceived - $total; }
    $orderResult = mysqli_query($conn, 'SELECT COALESCE(MAX(no_order), 0) + 1 AS next_order FROM tabel_transaksi FOR UPDATE'); $order = (int) mysqli_fetch_assoc($orderResult)['next_order'];
    $code = 'TRX' . date('YmdHis') . random_int(100, 999);
    $stmt = mysqli_prepare($conn, 'INSERT INTO tabel_transaksi (kode_transaksi, tanggal_transaksi, no_order, total, QRIS, tunai, kembalian, id_donasi) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)'); mysqli_stmt_bind_param($stmt, 'siddiii', $code, $order, $total, $qris, $cash, $change, $donationId); mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt);
    $stmt = mysqli_prepare($conn, 'INSERT INTO tabel_detail_transaksi (kode_transaksi, id_produk, id_kasir, harga, kuantitas, diskon, harga_jual) VALUES (?, ?, ?, ?, ?, ?, ?)'); mysqli_stmt_bind_param($stmt, 'siiiiii', $code, $productId, $cashierId, $unitPrice, $quantity, $unitDiscount, $lineTotal); mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt);
    mysqli_commit($conn); $transactionStarted = false; set_flash('success', 'Transaksi berhasil disimpan dengan kode ' . $code . '.'); redirect('pages/transactions/transaction_details.php?kode=' . rawurlencode($code));
} catch (InvalidArgumentException $exception) { if ($transactionStarted) mysqli_rollback($conn); set_flash('error', $exception->getMessage());
} catch (mysqli_sql_exception $exception) { if ($transactionStarted) mysqli_rollback($conn); error_log('Proses transaksi gagal: ' . $exception->getMessage()); set_flash('error', 'Transaksi tidak dapat disimpan saat ini.'); }
redirect('pages/transactions/add.php');
