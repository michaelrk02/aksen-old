<div>
    <div style="padding: 32px">
        <div id="order-page" class="w3-card w3-round <?php echo COLOR4; ?> w3-cell-row w3-mobile">
            <div class="w3-cell w3-cell-middle w3-mobile" style="width: 25%">
                <div class="w3-padding">
                    <h3><?php echo $title; ?></h3>
                    <h4>&raquo; Pesan Tiket</h4>
                </div>
            </div>
            <div class="w3-padding w3-cell w3-cell-middle <?php echo COLOR5; ?> w3-mobile">
                <h4>Tersedia <?php echo $available_tickets; ?> tiket</h4>
                <p>Kategori tiket: <b><?php echo $category->name; ?></b></p>
                <p>Harga satu tiket: <b><?php echo rupiah($category->price); ?></b></p>
                <p>E-mail anda: <u><?php echo $account->email; ?></u></p>
                <?php echo form_open('customer_v2/order/'.($alumni ? '1' : '0'), 'id="order-form" onsubmit="return confirm(\'Apakah anda yakin? Detail tiket yang anda pesan tidak dapat diubah setelah melakukan pembayaran\')"'); ?>
                    <input type="hidden" name="tickets" v-model="tickets">
                    <input type="hidden" name="visitors" v-model="visitorsJSON">
                    <div class="w3-section w3-border w3-round-large">
                        <ul class="w3-ul">
                            <li class="w3-cell-row">
                                <h3 class="w3-cell w3-cell-middle w3-mobile">Data calon pengunjung</h3>
                                <h6 class="w3-cell w3-cell-middle w3-mobile w3-right-align">Jumlah tiket: {{ visitors.length }}</h6>
                            </li>
                            <li v-for="(v, i) in visitors" class="w3-cell-row w3-animate-top">
                                <label>Pengunjung #{{ i + 1 }}:</label>
                                <div class="w3-cell-row">
                                    <div class="w3-cell w3-cell-middle w3-mobile w3-padding-small" style="width: 99%">
                                        <div class="w3-cell-row">
                                            <div class="w3-cell w3-cell-middle w3-mobile"><input type="text" class="w3-input w3-border" placeholder="Nama... (lengkap)" v-model="v.name"></div>
                                            <div class="w3-cell w3-cell-middle w3-mobile"><input type="text" class="w3-input w3-border" placeholder="<?php echo $alumni ? 'Tahun masuk...' : 'Asal sekolah...'; ?>" v-model="v.origin"></div>
                                        </div>
                                    </div>
                                    <div class="w3-cell w3-cell-middle w3-mobile w3-padding-small"><button type="button" class="w3-btn w3-red w3-round-xxlarge" v-on:click="removeVisitor(i)">Hapus</button></div>
                                </div>
                            </li>
                            <li class="w3-cell-row">
                                <div class="w3-cell w3-cell-middle w3-mobile">
                                    <i>*) <a href="<?php echo site_url('customer_v2/order/'.($alumni ? '0' : '1')); ?>" onclick="return confirm('Apakah anda yakin?')"><b>Klik di sini jika anda merupakan <?php echo $alumni ? 'siswa SMA/SMK/sederajat' : 'alumni SMAN 3 Surakarta'; ?></b></a></i><br>
                                    <i>*) Masukkan data calon pengunjung (maksimal 5) dengan meng-klik "Tambah"</i><br>
                                    <?php if ($alumni) { ?> <i>*) Bagi alumni yang masuk tahun 2011 dan sebelumnya <b>WAJIB</b> untuk membawa bukti identitas alumni pada hari pelaksanaan</i><br><?php } ?>
                                    <i>*) Data yang dimasukkan <b>harus</b> sesuai dengan <?php echo $alumni ? 'bukti identitas alumni yang akan dibawa' : 'kartu pelajar/kartu identitas sekolah lainnya (kartu pramuka, kartu tes, dll)'; ?> untuk pemrosesan saat check-in <b>(bagi data yang tidak cocok atau yang tidak membawa tanda pengenal, tidak diizinkan untuk masuk ke dalam lokasi event)</b></i><br>
                                    <i>*) Setelah selesai, klik "Check-out"</i><br>
                                </div>
                                <div class="w3-cell w3-cell-middle w3-mobile w3-right-align">
                                    <button type="button" class="w3-btn <?php echo COLOR6; ?> w3-round-xxlarge" v-on:click="addVisitor()" v-bind:disabled="!canAdd">Tambah</button>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="w3-section">
                        <button type="button" class="w3-btn w3-round-xxlarge <?php echo COLOR6; ?>" v-bind:disabled="!allowedToProceed" v-on:click="checkOut = true">Check-out</button>
                        &nbsp;<a href="<?php echo site_url('customer_v2/terms').'#order'; ?>" target="_blank">Ketentuan pemesanan</a>
                    </div>
                    <div class="w3-modal" v-if="checkOut" style="display: block">
                        <div class="w3-modal-content w3-card-4 w3-animate-top">
                            <span class="w3-button w3-display-topright" v-on:click="checkOut = false">&times;</span>
                            <div class="w3-padding-large w3-topbar w3-border-theme">
                                <h4>Periksa kembali detail pemesanan anda</h4>
                                 <div class="w3-container">
                                    <div class="w3-section">
                                        <ul class="w3-ul w3-border w3-round-large">
                                            <li><h4>Jumlah tiket: {{ visitors.length }} (<?php echo $category->name; ?>)</h4></li>
                                            <li v-for="(v, i) in visitors" class="w3-cell-row">
                                                <div class="w3-cell w3-cell-middle w3-mobile" style="width: 20%">
                                                    Pengunjung #{{ i + 1 }}:
                                                </div>
                                                <div class="w3-cell w3-cell-middle w3-mobile" style="width: 40%">
                                                    Nama: <b>{{ v.name }}</b>
                                                </div>
                                                <div class="w3-cell w3-cell-middle w3-mobile" style="width: 40%">
                                                    <?php echo $alumni ? 'Tahun masuk' : 'Asal sekolah'; ?>: <b>{{ v.origin }}</b>
                                                </div>
                                            </li>
                                            <li>
                                                <i>*) Periksa kembali detail nama (lengkap) dan asal sekolah agar sesuai dengan kartu pelajar/kartu identitas sekolah lainnya yang akan digunakan</i><br>
                                                <i>*) Harga yang harus dibayar: <b>IDR {{ totalPrice }} + kode unik</b></i><br>
                                                <i>*) Deadline transfer <b>1x24 jam</b> setelah pemesanan selesai dilakukan</i>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="w3-section"></div>
                                    <div class="w3-section">
                                        <input type="submit" class="w3-btn w3-round-xxlarge <?php echo COLOR6; ?>" v-bind:disabled="!allowedToProceed" value="Lanjut ke pembayaran">
                                        &nbsp;
                                        <a href="#null" v-on:click="checkOut = false">Batal</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

window.orderPage = new Vue({
    el: '#order-page',
    data: {
        availableTickets: <?php echo $available_tickets; ?>,
        visitors: [],
        checkOut: false
    },
    methods: {
        addVisitor: function() {
            this.visitors.push({name: '', origin: '', checkIn: false});
            this.$forceUpdate();
        },
        removeVisitor: function(n) {
            this.visitors.splice(n, 1);
            this.$forceUpdate();
        }
    },
    mounted: function() {
        <?php foreach ($visitors as $visitor) { ?>
            this.visitors.push({name: '<?php echo addslashes($visitor->name); ?>', origin: '<?php echo addslashes($visitor->origin); ?>', checkIn: false});
        <?php } ?>
    },
    computed: {
        tickets: function() {
            return this.visitors.length;
        },
        totalPrice: function() {
            return this.tickets * <?php echo $category->price; ?>;
        },
        canAdd: function() {
            return (this.tickets < 5) && (this.tickets < this.availableTickets);
        },
        allowedToProceed: function() {
            if (this.tickets > 0) {
                for (var visitor of this.visitors) {
                    if ((visitor.name === '') || (visitor.origin === '')) {
                        return false;
                    }
                }
                return true;
            }
            return false;
        },
        visitorsJSON: function() {
            return JSON.stringify(this.visitors);
        }
    }
});


</script>
