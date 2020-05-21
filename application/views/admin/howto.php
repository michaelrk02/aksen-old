<br>
<div class="w3-container">
    <h3>Panduan Administrasi</h3>
    <h6>Tahap penerimaan:</h6>
    <ol type="1">
        <li>Buka mutasi rekening</li>
        <li>Filter data untuk melihat mutasi yang masuk 1 hari sebelumnya (dapat disesuaikan dengan waktu konfirmasi terakhir)</li>
        <li>Lihat pada nominal transfer satu-persatu</li>
        <li>Lalu pada tab <a href="<?php echo site_url('admin/orders'); ?>">Daftar Pemesanan</a>, cari data yang ID pemesanannya (3 digit terakhir dari nominal transfer) sesuai dengan yang ada di mutasi rekening</li>
        <li>Setelah itu, klik <b>Terima</b> pada akun yang bersangkutan <b>JIKA</b> nominal sesuai. Jika tidak, maka tolak dengan alasan <b>nominal transfer tidak sesuai dengan yang ditentukan</b></li>
        <li>Ulangi dari langkah nomor <b>3</b> sampai sudah dicek semuanya</li>
        <li><b>(OPTIONAL)</b> Jika sudah selesai, ulangi dari langkah nomor <b>2</b> jika belum melakukan pengecekan pada hari-hari sebelumnya</li>
    </ol>
    <h6>Tahap penolakan: (langkah ini dapat dimulai ketika tahap penerimaan sudah dilakukan)</h6>
    <ol type="1">
        <li>Buka tab <a href="<?php echo site_url('admin/orders'); ?>">Daftar Pemesanan</a></li>
        <li>Ubah pengaturan sehingga opsi <i>Tampilkan semuanya</i> tidak dicentang</li>
        <li><b>Tolak</b> satu-persatu akun dengan alasan <b>belum melakukan pembayaran</b></li>
    </ol>
</div>
<br>
