<?php
require_once __DIR__ . '/../../config/config.php'; require_once __DIR__ . '/../../includes/functions.php';
$cashiers = mysqli_query($conn, 'SELECT id_kasir, nama_kasir FROM tabel_kasir ORDER BY nama_kasir ASC');
$products = mysqli_query($conn, 'SELECT id_produk, nama_produk, harga, diskon FROM tabel_produk ORDER BY nama_produk ASC');
$donations = mysqli_query($conn, 'SELECT id_donasi, nama_donasi, harga_donasi FROM tabel_donasi ORDER BY nama_donasi ASC');
require_once __DIR__ . '/../../includes/header.php';
?>
<section class="page-heading"><div><h2>Transaksi Baru</h2><p>Satu transaksi menyimpan satu produk, sesuai primary key detail transaksi.</p></div><a class="button button-secondary" href="<?= e(app_url('pages/transactions/list.php')) ?>">Riwayat Transaksi</a></section>
<?php if (mysqli_num_rows($cashiers) === 0 || mysqli_num_rows($products) === 0): ?>
    <p class="alert alert-error">Tambahkan minimal satu kasir dan satu produk sebelum membuat transaksi.</p>
<?php else: ?>
<div class="transaction-workspace">
<form class="form-card transaction-form" method="post" action="<?= e(app_url('process/transactions_process.php')) ?>">
    <input type="hidden" name="action" value="create">
    <div class="form-grid">
        <label class="form-group">Kasir<select name="id_kasir" required><option value="">Pilih kasir</option><?php while ($cashier = mysqli_fetch_assoc($cashiers)): ?><option value="<?= (int) $cashier['id_kasir'] ?>"><?= e($cashier['nama_kasir']) ?></option><?php endwhile; ?></select></label>
        <label class="form-group">Produk<select name="id_produk" data-product-select required><option value="">Pilih produk</option><?php while ($product = mysqli_fetch_assoc($products)): ?><option value="<?= (int) $product['id_produk'] ?>" data-price="<?= (int) $product['harga'] ?>" data-discount="<?= (int) $product['diskon'] ?>"><?= e($product['nama_produk']) ?> — <?= e(format_rupiah((int) $product['harga'] - (int) $product['diskon'])) ?></option><?php endwhile; ?></select></label>
        <label class="form-group">Kuantitas<input type="number" name="kuantitas" data-quantity-input min="1" step="1" value="1" required></label>
        <label class="form-group">Donasi (opsional)<select name="id_donasi" data-donation-select><option value="">Tanpa donasi</option><?php while ($donation = mysqli_fetch_assoc($donations)): ?><option value="<?= (int) $donation['id_donasi'] ?>" data-donation="<?= (int) $donation['harga_donasi'] ?>"><?= e($donation['nama_donasi']) ?> — <?= e(format_rupiah((int) $donation['harga_donasi'])) ?></option><?php endwhile; ?></select></label>
        <label class="form-group">Metode pembayaran<select name="metode_pembayaran" required><option value="qris">QRIS</option><option value="tunai">Tunai</option></select></label>
        <label class="form-group">Uang tunai diterima<input type="number" name="uang_bayar" min="0" step="1" value="0"><span class="help-text">Wajib diisi untuk pembayaran tunai. QRIS akan dibebankan sebesar total.</span></label>
    </div>
    <p class="help-text">Harga, diskon, total, dan kembalian dihitung ulang di server berdasarkan database.</p>
    <p class="actions"><button type="submit">Simpan Transaksi</button></p>
</form>
<aside class="transaction-summary" aria-live="polite">
    <span class="panel-eyebrow">Ringkasan pembayaran</span>
    <div class="summary-line"><span>Subtotal</span><strong data-summary-subtotal>Rp0</strong></div>
    <div class="summary-line"><span>Diskon</span><strong data-summary-discount>Rp0</strong></div>
    <div class="summary-line"><span>Donasi</span><strong data-summary-donation>Rp0</strong></div>
    <div class="summary-total"><span>Estimasi total</span><strong data-summary-total>Rp0</strong></div>
    <p>Nominal final selalu dihitung ulang oleh server saat transaksi disimpan.</p>
</aside>
</div>
<?php endif; mysqli_free_result($cashiers); mysqli_free_result($products); mysqli_free_result($donations); require_once __DIR__ . '/../../includes/footer.php'; ?>
