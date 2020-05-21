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
            <div class="w3-col w3-third">Filter:</div>
            <div class="w3-col w3-twothird"><input type="text" class="w3-input w3-border" placeholder="Filter ID pemesanan" v-model="filter"></div>
        </div>
        <br>
        <div class="w3-row">
            <div class="w3-col w3-third">Tanggal:</div>
            <div class="w3-col w3-twothird"><input type="date" class="w3-input w3-border" v-model="date"></div>
        </div>
        <br>
        <br>
        <a class="w3-btn <?php echo COLOR6; ?>" v-bind:href="parseURL">OK</a>
        <a href="#" v-on:click="shown = false">Batal</a><br>
        <br>
    </div>
</div>
<div id="orders-page" class="w3-container">
    <h3>Daftar Pembeli:</h3>
    Menampilkan halaman <?php echo $page; ?> dari <?php echo $pages; ?>. Total <?php echo $entries; ?> item (<a href="#null" v-on:click="showControls()">ubah</a>)
    <div class="w3-responsive">
        <table class="w3-table-all <?php echo COLOR3; ?>">
            <tr class="<?php echo COLOR2; ?>">
                <th>Waktu</th>
                <th>E-mail</th>
                <th>Tiket (jumlah)</th>
                <th>Harga total</th>
            </tr>
            <?php foreach ($accounts as $account) { ?>
                <tr>
                    <td><?php echo $account->accepted; ?></td>
                    <td><u><?php echo $account->email; ?></u></td>
                    <td><?php echo $categories[$account->id]->name; ?> (<?php echo $account->tickets; ?>)</td>
                    <td><b><?php echo rupiah($bill[$account->id]); ?></b></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="3"><b>PENDAPATAN TOTAL:</b></td>
                <td><?php echo rupiah($income); ?></td>
            </tr>
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
        date: '<?php echo $date; ?>',
        filter: '<?php echo $filter; ?>'
    },
    computed: {
        entries: function() {
            return <?php echo $entries; ?>;
        },
        pageNumbers: function() {
            var entries = <?php echo $entries; ?>;
            var pages = Math.ceil(entries / this.itemsPerPage);

            return (pages == 0) ? 1 : pages;
        },
        parseURL: function() {
            return '<?php echo site_url('admin/accounts'); ?>/' + this.itemsPerPage + '/' + this.page + '/' + (this.filter.length > 0 ? parseInt(this.filter) : '') + '?date=' + this.date;
        }
    }
});

window.ordersPage = new Vue({
    el: '#orders-page',
    methods: {
        showControls: function() {
            window.controlsModal.shown = true;
        }
    }
});

</script>
