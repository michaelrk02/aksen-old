<div class="w3-modal" id="visitors-modal" v-if="shown" style="display: block">
    <div class="w3-modal-content w3-card-4 w3-animate-top">
        <span class="w3-button w3-display-topright" v-on:click="shown = false">&times;</span>
        <div class="w3-padding-large w3-topbar w3-border-theme">
            <h4>Daftar calon pengunjung</h4>
             <div class="w3-container">
                <div class="w3-section">
                    <ul class="w3-ul w3-border w3-round-large">
                        <li v-for="(v, i) in visitors" class="w3-cell-row">
                            <div class="w3-cell w3-cell-middle w3-mobile" style="width: 20%">
                                Pengunjung #{{ i + 1 }}:
                            </div>
                            <div class="w3-cell w3-cell-middle w3-mobile" style="width: 40%">
                                Nama: <b>{{ v.name }}</b>
                            </div>
                            <div class="w3-cell w3-cell-middle w3-mobile" style="width: 40%">
                                Asal: <b>{{ v.origin }}</b>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="transfer-page">
    <div style="padding: 32px">
        <div id="transfer-page" class="w3-card w3-round <?php echo COLOR4; ?> w3-cell-row w3-mobile">
            <div class="w3-cell w3-cell-middle w3-mobile" style="width: 25%">
                <div class="w3-padding">
                    <h3><?php echo $title; ?></h3>
                    <h4>&raquo; Transfer ke Rekening Tujuan</h4>
                </div>
            </div>
            <div class="w3-padding w3-cell w3-cell-middle <?php echo COLOR5; ?> w3-mobile">
                <div class="w3-responsive">
                    <table class="w3-table-all w3-white">
                        <tr class="<?php echo COLOR1; ?>">
                            <th>Informasi</th>
                            <th></th>
                            <th>Keterangan</th>
                        </tr>
                        <tr>
                            <td>E-mail pemesan</td>
                            <td>:</td>
                            <td><u><?php echo $account->email; ?></u></td>
                        </tr>
                        <tr>
                            <td>Waktu memesan</td>
                            <td>:</td>
                            <td><?php echo date('r', $account->last_order); ?></td>
                        </tr>
                        <tr>
                            <td>Kategori tiket yang dipesan</td>
                            <td>:</td>
                            <td><?php echo $category->name; ?></td>
                        </tr>
                        <tr>
                            <td>Harga tiket per-satuan</td>
                            <td>:</td>
                            <td><?php echo rupiah($category->price); ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah tiket yang dipesan</td>
                            <td>:</td>
                            <td><?php echo $order->tickets; ?></td>
                        </tr>
                        <tr>
                            <td>Harga tiket total</td>
                            <td>:</td>
                            <td><?php echo rupiah($order->tickets * $category->price); ?></td>
                        </tr>
                        <tr>
                            <td>Kode unik transfer</td>
                            <td>:</td>
                            <td><?php echo sprintf('%03d', $order->order_id); ?></td>
                        </tr>
                        <tr>
                            <td>Daftar calon pengunjung</td>
                            <td>:</td>
                            <td><button class="w3-btn w3-round-xxlarge <?php echo COLOR6; ?>" v-on:click="showVisitors">Lihat</button></td>
                        </tr>
                        <tr>
                            <td><b>GRAND TOTAL</b></td>
                            <td>:</td>
                            <td><b><?php echo rupiah($bill); ?></b></td>
                        </tr>
                        <tr>
                            <td><b>Nomor rekening tujuan</b></td>
                            <td>:</td>
                            <td><b><?php echo $bank_account; ?></b></td>
                        </tr>
                        <tr>
                            <td><b>Deadline transfer</b></td>
                            <td>:</td>
                            <td><b><?php echo date('r', $account->last_order + 24 * 60 * 60); ?></b></td>
                        </tr>
                    </table>
                </div>
                <p>
                    <i>*) Mohon untuk dibayar sesuai dengan grand total</i><br>
                    <i>*) Anda akan mendapatkan e-tiket setelah pembayaran anda diproses oleh administrator (dalam waktu secepatnya 1x24 jam)</i><br>
                    <i>*) Pembayaran juga dapat dilakukan secara cash pada Ticket Box di aula SMA Negeri 3 Surakarta (lokasi kerkoff)</i><br>
                    <i>*) Pembayaran secara cash dilayani mulai pukul <?php echo $info->ticketbox_schedule; ?></i>
                </p>
            </div>
        </div>
    </div>
</div>
<script>

window.visitorsModal = new Vue({
    el: '#visitors-modal',
    data: {
        visitors: JSON.parse('<?php echo addslashes($account->visitors); ?>'),
        shown: false
    }
});

window.transferPage = new Vue({
    el: '#transfer-page',
    methods: {
        showVisitors: function() {
            window.visitorsModal.shown = true;
        }
    }
});

</script>
