<?php
require_once __DIR__ . '/functions.php';
ensure_session();
$flash = pull_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indomaret RPL4</title>
    <link rel="stylesheet" href="<?= e(app_url('assets/css/style.css')) ?>">
</head>
<body>
    <header>
        <h1>Indomaret RPL4</h1>
        <nav aria-label="Navigasi utama">
            <a href="<?= e(app_url('pages/dashboard.php')) ?>">Dashboard</a>
            <a href="<?= e(app_url('pages/products/list.php')) ?>">Produk</a>
            <a href="<?= e(app_url('pages/cashiers/list.php')) ?>">Kasir</a>
            <a href="<?= e(app_url('pages/donations/list.php')) ?>">Donasi</a>
            <a href="<?= e(app_url('pages/transactions/add.php')) ?>">Transaksi</a>
            <a href="<?= e(app_url('pages/transactions/list.php')) ?>">Riwayat</a>
        </nav>
    </header>
    <main>
        <?php if ($flash !== null): ?>
            <p class="alert alert-<?= e($flash['type'] === 'success' ? 'success' : 'error') ?>" role="alert"><?= e($flash['message']) ?></p>
        <?php endif; ?>
