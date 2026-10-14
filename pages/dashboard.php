<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$summary = ['products' => 0, 'cashiers' => 0, 'transactions' => 0, 'revenue' => 0];
try {
    $summary['products'] = (int) mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS total FROM tabel_produk'))['total'];
    $summary['cashiers'] = (int) mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS total FROM tabel_kasir'))['total'];
    $summary['transactions'] = (int) mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS total FROM tabel_transaksi'))['total'];
    $summary['revenue'] = (float) mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COALESCE(SUM(total), 0) AS total FROM tabel_transaksi'))['total'];
} catch (mysqli_sql_exception $exception) {
    error_log('Query dashboard gagal: ' . $exception->getMessage());
    $dashboardError = 'Ringkasan database tidak dapat dimuat saat ini.';
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-heading">
    <div>
        <h2>Dashboard</h2>
        <p>Ringkasan Point of Sales Indomaret RPL4.</p>
    </div>
    <a class="button" href="<?= e(app_url('pages/transactions/add.php')) ?>">Buat Transaksi</a>
</section>

<?php if (isset($dashboardError)): ?>
    <p class="alert alert-error" role="alert"><?= e($dashboardError) ?></p>
<?php else: ?>
    <section class="summary-grid" aria-label="Ringkasan aplikasi">
        <article><span>Produk</span><strong><?= $summary['products'] ?></strong></article>
        <article><span>Kasir</span><strong><?= $summary['cashiers'] ?></strong></article>
        <article><span>Transaksi</span><strong><?= $summary['transactions'] ?></strong></article>
        <article><span>Total Penjualan</span><strong><?= e(format_rupiah($summary['revenue'])) ?></strong></article>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
