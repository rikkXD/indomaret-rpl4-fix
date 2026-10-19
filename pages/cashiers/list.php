<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/functions.php';
$cashiers = mysqli_query($conn, 'SELECT id_kasir, nama_kasir, no_telp, alamat FROM tabel_kasir ORDER BY id_kasir ASC');
require_once __DIR__ . '/../../includes/header.php';
?>
<section class="page-heading"><div><h2>Daftar Kasir</h2><p>Kelola data kasir yang melayani transaksi.</p></div><a class="button" href="<?= e(app_url('pages/cashiers/add.php')) ?>">Tambah Kasir</a></section>
<?php if (mysqli_num_rows($cashiers) === 0): ?><p class="alert">Belum ada kasir.</p><?php else: ?>
<div class="table-wrapper"><table><thead><tr><th>ID</th><th>Nama</th><th>No. Telepon</th><th>Alamat</th><th>Aksi</th></tr></thead><tbody><?php while ($cashier = mysqli_fetch_assoc($cashiers)): ?><tr><td><?= (int) $cashier['id_kasir'] ?></td><td><?= e($cashier['nama_kasir']) ?></td><td><?= e($cashier['no_telp']) ?></td><td><?= e($cashier['alamat']) ?></td><td><div class="actions"><a class="button button-secondary" href="<?= e(app_url('pages/cashiers/edit.php?id=' . (int) $cashier['id_kasir'])) ?>">Edit</a><form class="inline-form" method="post" action="<?= e(app_url('process/cashiers_process.php')) ?>" data-confirm="Kasir ini akan dihapus bila belum digunakan pada transaksi."><input type="hidden" name="action" value="delete"><input type="hidden" name="id_kasir" value="<?= (int) $cashier['id_kasir'] ?>"><button class="button button-danger">Hapus</button></form></div></td></tr><?php endwhile; ?></tbody></table></div>
<?php endif; mysqli_free_result($cashiers); require_once __DIR__ . '/../../includes/footer.php'; ?>
