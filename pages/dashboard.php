<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$summary = ['products' => 0, 'cashiers' => 0, 'transactions' => 0, 'revenue' => 0];
$recentTransactions = null;
try {
    $summary['products'] = (int) mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS total FROM tabel_produk'))['total'];
    $summary['cashiers'] = (int) mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS total FROM tabel_kasir'))['total'];
    $summary['transactions'] = (int) mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COUNT(*) AS total FROM tabel_transaksi'))['total'];
    $summary['revenue'] = (float) mysqli_fetch_assoc(mysqli_query($conn, 'SELECT COALESCE(SUM(total), 0) AS total FROM tabel_transaksi'))['total'];
    $recentTransactions = mysqli_query($conn, 'SELECT t.kode_transaksi, t.tanggal_transaksi, t.total, t.QRIS, p.nama_produk FROM tabel_transaksi t INNER JOIN tabel_detail_transaksi dt ON dt.kode_transaksi = t.kode_transaksi INNER JOIN tabel_produk p ON p.id_produk = dt.id_produk ORDER BY t.tanggal_transaksi DESC, t.no_order DESC LIMIT 5');
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
        <article class="summary-card"><span class="summary-icon summary-icon-blue" aria-hidden="true">□</span><div><span>Produk</span><strong><?= $summary['products'] ?></strong><small>Produk terdaftar</small></div></article>
        <article class="summary-card"><span class="summary-icon summary-icon-yellow" aria-hidden="true">◉</span><div><span>Kasir</span><strong><?= $summary['cashiers'] ?></strong><small>Kasir aktif</small></div></article>
        <article class="summary-card"><span class="summary-icon summary-icon-red" aria-hidden="true">◷</span><div><span>Transaksi</span><strong><?= $summary['transactions'] ?></strong><small>Transaksi tercatat</small></div></article>
        <article class="summary-card summary-card-wide"><span class="summary-icon summary-icon-blue" aria-hidden="true">Rp</span><div><span>Total Penjualan</span><strong><?= e(format_rupiah($summary['revenue'])) ?></strong><small>Akumulasi seluruh transaksi</small></div></article>
    </section>

    <section class="dashboard-grid" aria-label="Aktivitas terbaru">
        <article class="dashboard-panel dashboard-quick-action">
            <span class="panel-eyebrow">Aksi cepat</span>
            <h3>Siap melayani pelanggan?</h3>
            <p>Pilih kasir, produk, dan metode pembayaran untuk mulai mencatat transaksi.</p>
            <a class="button" href="<?= e(app_url('pages/transactions/add.php')) ?>">Mulai Transaksi</a>
        </article>
        <article class="dashboard-panel dashboard-recent">
            <div class="panel-heading"><div><span class="panel-eyebrow">Aktivitas</span><h3>Transaksi terbaru</h3></div><a href="<?= e(app_url('pages/transactions/list.php')) ?>">Lihat riwayat</a></div>
            <?php if ($recentTransactions !== null && mysqli_num_rows($recentTransactions) > 0): ?>
                <div class="recent-list">
                    <?php while ($transaction = mysqli_fetch_assoc($recentTransactions)): ?>
                        <a class="recent-item" href="<?= e(app_url('pages/transactions/transaction_details.php?kode=' . rawurlencode($transaction['kode_transaksi']))) ?>">
                            <span class="recent-mark" aria-hidden="true">T</span>
                            <span><strong><?= e($transaction['kode_transaksi']) ?></strong><small><?= e($transaction['nama_produk']) ?></small></span>
                            <span class="recent-total"><strong><?= e(format_rupiah((float) $transaction['total'])) ?></strong><small><?= (float) $transaction['QRIS'] > 0 ? 'QRIS' : 'Tunai' ?></small></span>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="empty-state">Belum ada transaksi yang dapat ditampilkan.</p>
            <?php endif; ?>
        </article>
    </section>
<?php endif; ?>

<?php
if ($recentTransactions !== null) {
    mysqli_free_result($recentTransactions);
}
require_once __DIR__ . '/../includes/footer.php';
?>
