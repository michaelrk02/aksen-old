<script src="<?php echo base_url('public/qrcode.min.js'); ?>"></script>
<div>
    <div class="w3-cell-row w3-mobile">
        <div id="dashboard-page" class="w3-cell w3-cell-top w3-mobile w3-padding-large" style="width: 25%">
            <div class="w3-card w3-pale-yellow w3-border-yellow w3-leftbar">
                <div class="w3-padding">
                    <?php echo $info->highlight; ?>
                </div>
            </div>
        </div>
        <div class="w3-cell w3-cell-middle w3-mobile w3-padding-large">
            <div id="dashboard-page" class="w3-card w3-round <?php echo COLOR4; ?> w3-cell-row w3-mobile">
                <div class="w3-cell w3-cell-middle w3-mobile" style="width: 25%">
                    <div class="w3-padding">
                        <h3><?php echo $title; ?></h3>
                    </div>
                </div>
                <div class="w3-padding w3-cell w3-cell-middle <?php echo COLOR5; ?> w3-mobile">
                    <div class="w3-responsive">
                        <table class="w3-table-all w3-white">
                            <tr class="<?php echo COLOR1; ?>">
                                <th>Informasi</th>
                                <td></td>
                                <th>Keterangan</th>
                            </tr>
                            <tr>
                                <td>E-mail anda</td>
                                <td>:</td>
                                <td><u><?php echo $account->email; ?></u></td>
                            </tr>
                            <tr>
                                <td>Kategori tiket</td>
                                <td>:</td>
                                <td><?php echo $category->name; ?></td>
                            </tr>
                            <tr>
                                <td>Jumlah e-tiket</td>
                                <td>:</td>
                                <td><?php echo $account->tickets; ?></td>
                            </tr>
                            <tr>
                                <td>Jumlah pengunjung check-in</td>
                                <td>:</td>
                                <td><?php echo $account->check_ins; ?> dari <?php echo $account->tickets; ?></td>
                            </tr>
                            <tr>
                                <td>E-tiket</td>
                                <td>:</td>
                                <td><a class="w3-btn w3-round-xxlarge <?php echo COLOR6; ?>" href="#tickets">Lihat</a></td>
                            </tr>
                            <tr>
                                <td>Ketentuan mengenai e-tiket</td>
                                <td>:</td>
                                <td><a class="w3-btn w3-round-xxlarge <?php echo COLOR6; ?>" href="<?php echo site_url('customer_v2/terms').'#e-tiket'; ?>" target="_blank">Lihat</a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div style="padding-top: 32px"></div>
            <div id="tickets" class="w3-card w3-round <?php echo COLOR4; ?> w3-cell-row w3-mobile">
                <div class="w3-cell w3-cell-middle w3-mobile" style="width: 25%">
                    <div class="w3-padding">
                        <h3>e-Tiket</h3>
                    </div>
                </div>
                <div class="w3-padding w3-cell w3-cell-middle <?php echo COLOR5; ?> w3-mobile">
                    <div class="w3-responsive">
                        <div class="w3-section w3-border w3-round-large">
                            <ul class="w3-ul">
                                <li class="w3-cell-row">
                                    <h3 class="w3-cell w3-cell-middle w3-mobile">E-tiket calon pengunjung</h3>
                                    <h6 class="w3-cell w3-cell-middle w3-mobile w3-right-align">Jumlah: <?php echo $account->tickets; ?></h6>
                                </li>
                                <?php foreach ($visitors as $i => $visitor) { ?>
                                    <li class="w3-cell-row">
                                        <div class="w3-cell w3-cell-middle w3-mobile">
                                            <div id="qrcode-<?php echo $i; ?>"></div>
                                        </div>
                                        <div class="w3-cell w3-cell-top w3-mobile" style="width: 99%">
                                            <table class="w3-white w3-padding">
                                                <tr>
                                                    <td>Nama lengkap</td>
                                                    <td>:</td>
                                                    <td><?php echo $visitor->name; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $alumni ? 'Tahun masuk' : 'Asal sekolah'; ?></td>
                                                    <td>:</td>
                                                    <td><?php echo $visitor->origin; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Website</td>
                                                    <td>:</td>
                                                    <td><?php echo base_url(); ?></td>
                                                </tr>
                                            </table>
                                            <i class="w3-padding">*) Syarat dan ketentuan berlaku (tertera di website)</i>
                                        </div>
                                    </li>
                                <?php } ?>
                                <li>
                                    <i><b>*) PENTING: calon pengunjung wajib membawa <?php echo $alumni ? 'bukti identitas alumni SMAN 3 Surakarta' : 'kartu pelajar masing-masing/kartu identitas sekolah lainnya (kartu pramuka, dll)'; ?> ke lokasi event untuk mencocokkan data e-tiket</b></i><br>
                                    <i>*) Dilarang membawa senjata, rokok, minuman keras, dan/atau obat-obatan terlarang ke dalam lokasi event</i><br>
                                    <i>*) Panitia berhak untuk tidak memberikan izin untuk masuk ke dalam lokasi event apabila tidak memenuhi ketentuan-ketentuan di atas</i><br>
                                    <i>**) E-tiket dapat di-screencapture untuk dibagikan</i>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

<?php foreach ($visitors as $i => $visitor) { ?>
    window.addEventListener('load', function() {
        new QRCode('qrcode-<?php echo $i; ?>', {
            text: '<?php echo $login; ?>/<?php echo $i; ?>',
            width: 128,
            height: 128,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });

        var qrcodeElement = document.getElementById('qrcode-<?php echo $i; ?>');
        var qrcodeImageElement = qrcodeElement.querySelector('img');
        qrcodeElement.title = '';
        qrcodeImageElement.title = '';
    });
<?php } ?>

</script>
