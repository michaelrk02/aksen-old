<div style="padding: 32px">
    <?php if (isset($account)) { ?>
        <div class="w3-border w3-round-xxlarge w3-white w3-padding">
            <h3>Check-in Pengunjung</h3>
            <table class="w3-table-all w3-white w3-section">
                <tr class="<?php echo COLOR1; ?>">
                    <th>Informasi</th>
                    <th></th>
                    <th>Keterangan</th>
                </tr>
                <tr>
                    <td>Nama lengkap</td>
                    <td>:</td>
                    <td><b><?php echo $visitor->name; ?></b></td>
                </tr>
                <tr>
                    <td>Asal sekolah</td>
                    <td>:</td>
                    <td><b><?php echo $visitor->origin; ?></b></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td><b><?php echo $visitor->checkIn ? 'sudah' : 'belum'; ?> check-in</b></td>
                </tr>
                <tr>
                    <td>E-mail pemesan</td>
                    <td>:</td>
                    <td><u><?php echo $account->email; ?></u></td>
                </tr>
                <tr>
                    <td>Kategori tiket</td>
                    <td>:</td>
                    <td><?php echo $category->name; ?></td>
                </tr>
                <tr>
                    <td>Total check-in</td>
                    <td>:</td>
                    <td><?php echo $account->check_ins; ?>/<?php echo $account->tickets; ?></td>
                </tr>
            </table>
            <div class="w3-section">
                <a onclick="<?php if (!$visitor->checkIn) { ?>return confirm('Apakah anda yakin?');<?php } else {?>return false<?php } ?>" class="w3-btn w3-round-xxlarge <?php echo COLOR6; ?> <?php echo $visitor->checkIn ? 'w3-disabled' : '' ?>" href="<?php echo site_url('admin/check_in_confirm/'.$account->id.'/'.$visitor_id); ?>">Check-in</a>
                &nbsp;<a href="<?php echo site_url('admin/check_in'); ?>">Batal</a>
            </div>
        </div>
        <div style="padding-top: 16px"></div>
    <?php } ?>
    <div id="check-in-page" class="w3-border w3-round-xxlarge w3-white w3-padding">
        <h3>Cari Akun</h3>
        <div class="w3-cell-row">
            <div class="w3-cell w3-cell-middle w3-mobile w3-padding">
                <video class="w3-black" id="qr-scanner" width="320" height="240"></video><br>
                <input type="checkbox" class="w3-check" v-model="enableScanner">&nbsp;Aktifkan scanner<br>
            </div>
            <div class="w3-cell w3-cell-top w3-mobile w3-padding" style="width: 99%">
                <?php echo form_open('admin/check_in', 'id="check-in-form"'); ?>
                    <label for="account-id">ID akun:</label>
                    <input id="account-id" name="admin_account_id" type="text" class="w3-input w3-border" style="font-family: monospace" v-model="accountID"><br>
                    <br>
                    <input type="submit" class="w3-btn <?php echo COLOR6; ?>" value="Cari">
                </form>
            </div>
        </div>
    </div>
</div>

<script type="module">

import QrScanner from '../../public/qr-scanner.min.js';

QrScanner.WORKER_PATH = '../../public/qr-scanner-worker.min.js';

window.qrScanner = undefined;
window.qrScannerElement = undefined;

window.addEventListener('load', () => {
    window.qrScannerElement = document.querySelector('#qr-scanner');
});

window.checkInPage = new Vue({
    el: '#check-in-page',
    data: {
        enableScanner: false,
        accountID: ''
    },
    watch: {
        enableScanner: function(value) {
            if (value) {
                if (!window.qrScanner) {
                    window.qrScanner = new QrScanner(window.qrScannerElement, (result) => {
                        window.checkInPage.enableScanner = false;

                        const form = document.querySelector('#check-in-form');
                        form.elements['admin_account_id'].value = result;
                        form.submit();
                    });
                    window.qrScanner.start();
                }
            } else {
                if (window.qrScanner) {
                    window.qrScanner.destroy();
                    window.qrScanner = undefined;
                }
            }
        }
    }
});

</script>
