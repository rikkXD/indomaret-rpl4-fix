<?php
require_once __DIR__ . '/../../config/config.php'; require_once __DIR__ . '/../../includes/functions.php';
$id = valid_positive_int($_GET['id'] ?? null); $donation = null;
if ($id !== null) { $stmt = mysqli_prepare($conn, 'SELECT id_donasi, nama_donasi, harga_donasi FROM tabel_donasi WHERE id_donasi = ?'); mysqli_stmt_bind_param($stmt, 'i', $id); mysqli_stmt_execute($stmt); $donation = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); mysqli_stmt_close($stmt); }
if ($donation === null) { set_flash('error', 'Donasi tidak ditemukan.'); redirect('pages/donations/list.php'); }
require_once __DIR__ . '/../../includes/header.php';
?>
<section class="page-heading"><div><h2>Edit Donasi</h2><p>Perbarui nama atau nominal donasi.</p></div></section>
<form class="form-card" method="post" action="<?= e(app_url('process/donations_process.php')) ?>"><input type="hidden" name="action" value="update"><input type="hidden" name="id_donasi" value="<?= (int) $donation['id_donasi'] ?>"><div class="form-grid"><label class="form-group form-group-full">Nama donasi<input name="nama_donasi" maxlength="50" value="<?= e($donation['nama_donasi']) ?>" required autofocus></label><label class="form-group">Nominal donasi<input type="number" name="harga_donasi" min="0" step="1" value="<?= (int) $donation['harga_donasi'] ?>" required></label></div><p class="actions"><button>Simpan Perubahan</button><a class="button button-secondary" href="<?= e(app_url('pages/donations/list.php')) ?>">Batal</a></p></form>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
