<?php

$current_url = urlencode(base64_encode(current_url()));

?>
<div id="controls-modal" style="display: block" class="w3-modal" v-if="shown">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom w3-container <?php echo COLOR3; ?>">
        <span class="w3-display-topright w3-button" v-on:click="shown = false">&times;</span>
        <h3>Kontrol</h3>
        <br>
        <div class="w3-row">
            <div class="w3-col w3-third">Total entri:</div>
            <div class="w3-col w3-twothird"><b>{{ entries }}</b> item</div>
        </div>
        <br>
        <div class="w3-row">
            <div class="w3-col w3-third">Item per halaman:</div>
            <div class="w3-col w3-twothird"><input class="w3-input w3-border" type="number" min="1" v-model="itemsPerPage"></div>
        </div>
        <br>
        <div class="w3-row">
            <div class="w3-col w3-third">Halaman:</div>
            <div class="w3-col w3-twothird">
                <select v-model="page" class="w3-select w3-border">
                    <option v-for="pageNumber in pageNumbers">{{ pageNumber }}</option>
                </select>
            </div>
        </div>
        <br>
        <div class="w3-row">
            <div class="w3-col w3-third">Tampilkan semuanya:</div>
            <div class="w3-col w3-twothird"><input type="checkbox" class="w3-check" v-model="viewAll"></div>
        </div>
        <br>
        <div class="w3-row">
            <div class="w3-col w3-third">Filter:</div>
            <div class="w3-col w3-twothird"><input type="text" class="w3-input w3-border" placeholder="Filter ID pemesanan" v-model="filter"></div>
        </div>
        <br>
        <br>
        <a class="w3-btn <?php echo COLOR6; ?>" v-bind:href="parseURL">OK</a>
        <a href="#" v-on:click="shown = false">Batal</a><br>
        <br>
    </div>
</div>
<div id="reject-modal" style="display: block" class="w3-modal" v-if="shown">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom w3-container <?php echo COLOR3; ?>">
        <span class="w3-display-topright w3-button" v-on:click="shown = false">&times;</span>
        <h3>Yakin untuk Ditolak?</h3>
        <br>
        <div class="w3-row">
            <div class="w3-col w3-third">Akun:</div>
            <div class="w3-col w3-twothird"><code>{{ accountID }}</code> (<u>{{ email }}</u>)</div>
        </div>
        <br>
        <div class="w3-row">
            <div class="w3-col w3-third">Alasan:</div>
            <div class="w3-col w3-twothird">
                <select class="w3-select" v-model="reasonID">
                    <option value="0">-- Pilih salah satu --</option>
                    <?php foreach ($reasons as $reason) { ?>
                        <option value="<?php echo $reason['id']; ?>"><?php echo $reason['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <br>
        <a v-bind:href="parseURL" class="w3-btn w3-green" onclick="return yaqueen()">OK</a>
        <a v-on:click="shown = false" class="w3-btn w3-red">BATAL</a><br>
        <br>
    </div>
</div>
<div class="w3-modal" id="record-modal" v-if="shown" style="display: block">
    <div class="w3-modal-content w3-card-4 w3-animate-top">
        <span class="w3-button w3-display-topright" v-on:click="shown = false">&times;</span>
        <div class="w3-padding-large w3-topbar w3-border-theme">
            <h4>Detail pemesanan</h4>
             <div class="w3-container">
                <div class="w3-section">
                    <ul class="w3-ul w3-border w3-round-large">
                        <li>E-mail pemesan: <u>{{ email }}</u></li>
                        <li>Kategori tiket: {{ category }}</li>
                        <li>Jumlah tiket: {{ visitors[accountID].length }}</li>
                        <li v-for="(v, i) in visitors[accountID]" class="w3-cell-row">
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
<div id="orders-page" class="w3-container">
    <h3>Daftar Pesanan:</h3>
    Menampilkan halaman <?php echo $page; ?> dari <?php echo $pages; ?>. Total <?php echo $entries; ?> item, <?php echo !$view_all ? 'tidak' : '' ?> menampilkan semuanya (<a href="#null" v-on:click="showControls()">ubah</a>)
    <div class="w3-responsive">
        <table class="w3-table-all <?php echo COLOR3; ?>">
            <tr class="<?php echo COLOR2; ?>">
                <th>ID Pesanan</th>
                <th>E-mail</th>
                <th>Waktu Memesan</th>
                <th>Tiket (jumlah)</th>
                <th>Total Tagihan</th>
                <th>Tindakan</th>
            </tr>
            <?php foreach ($orders as $order) { ?>
                <tr>
                    <td><?php echo order_id($order->order_id); ?></td>
                    <td><u><?php echo $order->email; ?></u></td>
                    <td><?php echo date('r', $order->last_order); ?></td>
                    <td><?php echo $categories[$order->id]->name; ?> (<?php echo $order->tickets; ?>)</td>
                    <td><b><?php echo rupiah($bill[$order->id]); ?></b></td>
                    <td>
                        <a href="<?php echo site_url('admin/order_accept/'.$order->id.'/'.$current_url); ?>" class="w3-btn w3-green" onclick="return yaqueen()">Terima</a>
                        <a class="w3-btn w3-red" v-on:click="reject('<?php echo $order->id; ?>', '<?php echo $order->email; ?>')">Tolak</a>
                        <a class="w3-btn w3-blue" v-on:click="showDetails('<?php echo $order->id; ?>', '<?php echo $order->email; ?>', '<?php echo addslashes($categories[$order->id]->name); ?>')">Lihat</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
<br>

<script>

window.controlsModal = new Vue({
    el: '#controls-modal',
    data: {
        shown: false,
        itemsPerPage: <?php echo $items_per_page; ?>,
        page: <?php echo $page; ?>,
        viewAll: <?php echo $view_all ? 'true' : 'false'; ?>,
        filter: '<?php echo $filter; ?>'
    },
    computed: {
        entries: function() {
            return this.viewAll ? <?php echo $entries_all; ?> : <?php echo $entries_few; ?>;
        },
        pageNumbers: function() {
            var entries = this.viewAll ? <?php echo $entries_all; ?> : <?php echo $entries_few; ?>;
            var pages = Math.ceil(entries / this.itemsPerPage);

            return (pages == 0) ? 1 : pages;
        },
        parseURL: function() {
            return '<?php echo site_url('admin/orders'); ?>/' + this.itemsPerPage + '/' + this.page + '/' + (this.viewAll ? 'yes' : 'no') + '/' + (this.filter.length > 0 ? parseInt(this.filter) : '');
        }
    }
});

window.rejectModal = new Vue({
    el: '#reject-modal',
    data: {
        accountID: undefined,
        email: undefined,
        shown: false,
        reasonID: 0
    },
    computed: {
        parseURL: function() {
            return '<?php echo site_url('admin/order_reject'); ?>/' + this.accountID + '/' + this.reasonID + '/<?php echo $current_url; ?>';
        }
    }
});

window.recordModal = new Vue({
    el: '#record-modal',
    data: {
        visitors: {},
        accountID: null,
        category: null,
        email: null,
        shown: false
    },
    mounted: function() {
        <?php foreach ($orders as $order) { ?>
            this.visitors['<?php echo $order->id; ?>'] = JSON.parse('<?php echo addslashes($order->visitors); ?>');
        <?php } ?>
    }
});

window.ordersPage = new Vue({
    el: '#orders-page',
    methods: {
        showControls: function() {
            window.controlsModal.shown = true;
        },
        reject: function(accountID, email) {
            window.rejectModal.accountID = accountID;
            window.rejectModal.email = email;
            window.rejectModal.shown = true;
        },
        showDetails: function(accountID, email, category) {
            window.recordModal.accountID = accountID;
            window.recordModal.email = email;
            window.recordModal.category = category;
            window.recordModal.shown = true;
        }
    }
});

</script>
