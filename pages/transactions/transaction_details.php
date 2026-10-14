<?php
require_once __DIR__ . '/../../config/config.php'; require_once __DIR__ . '/../../includes/functions.php';
$code = trim((string) ($_GET['kode'] ?? '')); $transaction = null;
if ($code !== '' && mb_strlen($code) <= 50) {
    $stmt = mysqli_prepare($conn, 'SELECT t.kode_transaksi, t.tanggal_transaksi, t.no_order, t.total, t.QRIS, t.tunai, t.kembalian, p.nama_produk, dt.harga, dt.kuantitas, dt.diskon, dt.harga_jual, k.nama_kasir, d.nama_donasi, d.harga_donasi FROM tabel_transaksi t INNER JOIN tabel_detail_transaksi dt ON dt.kode_transaksi = t.kode_transaksi INNER JOIN tabel_produk p ON p.id_produk = dt.id_produk INNER JOIN tabel_kasir k ON k.id_kasir = dt.id_kasir LEFT JOIN tabel_donasi d ON d.id_donasi = t.id_donasi WHERE t.kode_transaksi = ?');
    mysqli_stmt_bind_param($stmt, 's', $code); mysqli_stmt_execute($stmt); $transaction = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); mysqli_stmt_close($stmt);
}
if ($transaction === null) { set_flash('error', 'Transaksi tidak ditemukan.'); redirect('pages/transactions/list.php'); }
require_once __DIR__ . '/../../includes/header.php';
?>
<section class="page-heading"><div><h2>Detail Transaksi</h2><p>Kode: <?= e($transaction['kode_transaksi']) ?></p></div><a class="button button-secondary" href="<?= e(app_url('pages/transactions/list.php')) ?>">Kembali</a></section>
<div class="table-wrapper"><table><tbody>
<tr><th>No. Order</th><td><?= (int) $transaction['no_order'] ?></td></tr><tr><th>Tanggal</th><td><?= e(date('d-m-Y H:i', strtotime($transaction['tanggal_transaksi']))) ?></td></tr><tr><th>Kasir</th><td><?= e($transaction['nama_kasir']) ?></td></tr><tr><th>Produk</th><td><?= e($transaction['nama_produk']) ?></td></tr><tr><th>Harga satuan</th><td><?= e(format_rupiah((int) $transaction['harga'])) ?></td></tr><tr><th>Kuantitas</th><td><?= (int) $transaction['kuantitas'] ?></td></tr><tr><th>Diskon per unit</th><td><?= e(format_rupiah((int) $transaction['diskon'])) ?></td></tr><tr><th>Subtotal setelah diskon</th><td><?= e(format_rupiah((int) $transaction['harga_jual'])) ?></td></tr><tr><th>Donasi</th><td><?= $transaction['nama_donasi'] === null ? '-' : e($transaction['nama_donasi'] . ' (' . format_rupiah((int) $transaction['harga_donasi']) . ')') ?></td></tr><tr><th>Total bayar</th><td><?= e(format_rupiah((float) $transaction['total'])) ?></td></tr><tr><th>Metode pembayaran</th><td><?= (float) $transaction['QRIS'] > 0 ? 'QRIS' : 'Tunai' ?></td></tr><tr><th>Nominal dibayar</th><td><?= e(format_rupiah((float) $transaction['QRIS'] > 0 ? (float) $transaction['QRIS'] : (int) $transaction['tunai'])) ?></td></tr><tr><th>Kembalian</th><td><?= e(format_rupiah((int) $transaction['kembalian'])) ?></td></tr>
</tbody></table></div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
