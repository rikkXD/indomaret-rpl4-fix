<?php
require_once __DIR__ . '/functions.php';
ensure_session();
$flash = pull_flash();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$navigation = [
    ['path' => 'pages/dashboard.php', 'label' => 'Dashboard', 'icon' => '▦'],
    ['path' => 'pages/products/list.php', 'label' => 'Produk', 'icon' => '□'],
    ['path' => 'pages/cashiers/list.php', 'label' => 'Kasir', 'icon' => '◉'],
    ['path' => 'pages/donations/list.php', 'label' => 'Donasi', 'icon' => '♡'],
    ['path' => 'pages/transactions/add.php', 'label' => 'Transaksi', 'icon' => '＋'],
    ['path' => 'pages/transactions/list.php', 'label' => 'Riwayat', 'icon' => '◷'],
];
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
    <div class="app-shell">
        <aside class="sidebar" id="sidebar" aria-label="Navigasi aplikasi">
            <a class="brand" href="<?= e(app_url('pages/dashboard.php')) ?>" aria-label="Indomaret RPL4, ke Dashboard">
                <span class="brand-mark" aria-hidden="true">IM</span>
                <span class="brand-copy"><strong>Indomaret</strong><small>POS RPL4</small></span>
            </a>
            <nav class="sidebar-nav" aria-label="Navigasi utama">
                <?php foreach ($navigation as $item): ?>
                    <?php $isActive = str_ends_with($currentPath, '/' . $item['path']); ?>
                    <a class="nav-link<?= $isActive ? ' is-active' : '' ?>" href="<?= e(app_url($item['path'])) ?>"<?= $isActive ? ' aria-current="page"' : '' ?>>
                        <span class="nav-icon" aria-hidden="true"><?= e($item['icon']) ?></span>
                        <span><?= e($item['label']) ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
            <p class="sidebar-note">Kasir cepat, data rapi.</p>
        </aside>
        <div class="content-shell">
            <header class="topbar">
                <button class="menu-toggle" type="button" aria-label="Buka navigasi" aria-expanded="false" aria-controls="sidebar" data-sidebar-toggle><span></span><span></span><span></span></button>
                <div class="topbar-title"><span>Point of Sale</span><strong>Indomaret RPL4</strong></div>
                <a class="topbar-action" href="<?= e(app_url('pages/transactions/add.php')) ?>">+ Transaksi</a>
            </header>
            <main class="main-content" id="main-content">
        <?php if ($flash !== null): ?>
            <div class="alert alert-<?= e($flash['type'] === 'success' ? 'success' : 'error') ?>" role="alert">
                <span class="alert-icon" aria-hidden="true"><?= $flash['type'] === 'success' ? '✓' : '!' ?></span>
                <span><?= e($flash['message']) ?></span>
                <button class="alert-close" type="button" aria-label="Tutup notifikasi" data-dismiss-alert>×</button>
            </div>
        <?php endif; ?>
