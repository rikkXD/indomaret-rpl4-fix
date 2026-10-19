<?php
require_once __DIR__ . '/../../config/config.php';

try {
    $result = mysqli_query(
        $conn,
        'SELECT id_produk, nama_produk, harga, diskon FROM tabel_produk ORDER BY id_produk ASC'
    );
} catch (mysqli_sql_exception $exception) {
    error_log('Query daftar produk gagal: ' . $exception->getMessage());
    http_response_code(500);
    $queryError = 'Data produk tidak dapat dimuat saat ini.';
}

require_once __DIR__ . '/../../includes/header.php';
?>

<section class="page-heading">
    <div>
        <h2>Daftar Produk</h2>
        <p>Data produk yang tersimpan di database Indomaret RPL4.</p>
    </div>
    <a class="button" href="<?= e(app_url('pages/products/add.php')) ?>">Tambah Produk</a>
</section>

<?php if (isset($queryError)): ?>
    <p class="alert alert-error" role="alert"><?= htmlspecialchars($queryError, ENT_QUOTES, 'UTF-8') ?></p>
<?php elseif (mysqli_num_rows($result) === 0): ?>
    <p class="alert">Belum ada produk yang tersedia.</p>
<?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nama Produk</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Diskon</th>
                    <th scope="col">Harga Setelah Diskon</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = mysqli_fetch_assoc($result)): ?>
                    <?php
                    $price = (int) ($product['harga'] ?? 0);
                    $discount = (int) ($product['diskon'] ?? 0);
                    $finalPrice = max(0, $price - $discount);
                    ?>
                    <tr>
                        <td><?= (int) $product['id_produk'] ?></td>
                        <td><?= htmlspecialchars($product['nama_produk'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>Rp<?= number_format($price, 0, ',', '.') ?></td>
                        <td>Rp<?= number_format($discount, 0, ',', '.') ?></td>
                        <td>Rp<?= number_format($finalPrice, 0, ',', '.') ?></td>
                        <td><div class="actions"><a class="button button-secondary" href="<?= e(app_url('pages/products/edit.php?id=' . (int) $product['id_produk'])) ?>">Edit</a><form class="inline-form" method="post" action="<?= e(app_url('process/products_process.php')) ?>" data-confirm="Produk ini akan dihapus bila belum digunakan pada transaksi."><input type="hidden" name="action" value="delete"><input type="hidden" name="id_produk" value="<?= (int) $product['id_produk'] ?>"><button class="button button-danger" type="submit">Hapus</button></form></div></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php
if (isset($result)) {
    mysqli_free_result($result);
}

require_once __DIR__ . '/../../includes/footer.php';
