<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/functions.php';
$id = valid_positive_int($_GET['id'] ?? null);
$product = null;
if ($id !== null) { $stmt = mysqli_prepare($conn, 'SELECT id_produk, nama_produk, harga, diskon FROM tabel_produk WHERE id_produk = ?'); mysqli_stmt_bind_param($stmt, 'i', $id); mysqli_stmt_execute($stmt); $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)); mysqli_stmt_close($stmt); }
if ($product === null) { set_flash('error', 'Produk tidak ditemukan.'); redirect('pages/products/list.php'); }
require_once __DIR__ . '/../../includes/header.php';
?>
<section class="page-heading"><div><h2>Edit Produk</h2><p>Perbarui informasi produk.</p></div></section>
<form class="form-card" method="post" action="<?= e(app_url('process/products_process.php')) ?>">
    <input type="hidden" name="action" value="update"><input type="hidden" name="id_produk" value="<?= (int) $product['id_produk'] ?>">
    <div class="form-grid">
        <label class="form-group form-group-full">Nama produk<input type="text" name="nama_produk" maxlength="50" value="<?= e($product['nama_produk']) ?>" required autofocus></label>
        <label class="form-group">Harga<input type="number" name="harga" min="0" step="1" value="<?= (int) $product['harga'] ?>" required></label>
        <label class="form-group">Diskon per unit<input type="number" name="diskon" min="0" step="1" value="<?= (int) $product['diskon'] ?>" required></label>
    </div>
    <p class="actions"><button type="submit">Simpan Perubahan</button><a class="button button-secondary" href="<?= e(app_url('pages/products/list.php')) ?>">Batal</a></p>
</form>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
