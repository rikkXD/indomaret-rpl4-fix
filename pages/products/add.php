<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<section class="page-heading"><div><h2>Tambah Produk</h2><p>Masukkan harga dalam Rupiah tanpa pemisah.</p></div></section>
<form class="form-card" method="post" action="<?= e(app_url('process/products_process.php')) ?>">
    <input type="hidden" name="action" value="create">
    <div class="form-grid">
        <label class="form-group form-group-full">Nama produk<input type="text" name="nama_produk" maxlength="50" placeholder="Contoh: Indomie Goreng" required autofocus></label>
        <label class="form-group">Harga<input type="number" name="harga" min="0" step="1" placeholder="0" required></label>
        <label class="form-group">Diskon per unit<input type="number" name="diskon" min="0" step="1" value="0" required></label>
    </div>
    <p class="actions"><button type="submit">Simpan Produk</button><a class="button button-secondary" href="<?= e(app_url('pages/products/list.php')) ?>">Batal</a></p>
</form>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
