# Struktur Aplikasi Indomaret RPL4

## Penjelasan folder dan file

- `config/`: Menyimpan konfigurasi aplikasi, khususnya detail koneksi database, agar terpisah dari logika program.
- `assets/`: Berisi aset pendukung tampilan. Folder `css/` untuk stylesheet, `js/` untuk JavaScript, dan `img/` untuk gambar atau ikon.
- `includes/`: Menyimpan bagian halaman yang dipakai berulang, seperti header dan footer, supaya mudah digunakan kembali.
- `pages/`: Kumpulan halaman antarmuka sesuai fitur aplikasi: dashboard, kasir, donasi, produk, dan transaksi.
- `process/`: Berisi proses CRUD untuk memisahkan pengolahan data dari tampilan halaman.
- `index.php`: Pintu masuk aplikasi yang mengarahkan pengguna ke dashboard.

Struktur ini membantu menjaga aplikasi Point of Sales prosedural tetap rapi, mudah dikembangkan, dan mudah dipelihara.
