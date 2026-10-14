<?php
require_once __DIR__ . '/../../config/config.php'; require_once __DIR__ . '/../../includes/functions.php';
$id = valid_positive_int($_GET['id'] ?? null); $cashier = null;
if ($id !== null) { $stmt = mysqli_prepare($conn, 'SELECT id_kasir, nama_kasir, no_telp, alamat FROM tabel_kasir WHERE id_kasir = ?'); mysqli_stmt_bind_param($stmt, 'i', $id); mysqli_stmt_execute($stmt); $cashier = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); mysqli_stmt_close($stmt); }
if ($cashier === null) { set_flash('error', 'Kasir tidak ditemukan.'); redirect('pages/cashiers/list.php'); }
require_once __DIR__ . '/../../includes/header.php';
?>
<section class="page-heading"><div><h2>Edit Kasir</h2><p>Perbarui identitas kasir.</p></div></section>
<form class="form-card" method="post" action="<?= e(app_url('process/cashiers_process.php')) ?>"><input type="hidden" name="action" value="update"><input type="hidden" name="id_kasir" value="<?= (int) $cashier['id_kasir'] ?>"><div class="form-grid"><label class="form-group form-group-full">Nama kasir<input name="nama_kasir" maxlength="50" value="<?= e($cashier['nama_kasir']) ?>" required autofocus></label><label class="form-group">No. telepon<input name="no_telp" maxlength="20" value="<?= e($cashier['no_telp']) ?>" required></label><label class="form-group form-group-full">Alamat<textarea name="alamat" required><?= e($cashier['alamat']) ?></textarea></label></div><p class="actions"><button>Simpan Perubahan</button><a class="button button-secondary" href="<?= e(app_url('pages/cashiers/list.php')) ?>">Batal</a></p></form>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
