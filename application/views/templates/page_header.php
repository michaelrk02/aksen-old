<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="<?php echo base_url('public/vue.min.js'); ?>"></script>
        <link rel="stylesheet" href="<?php echo base_url('public/w3.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('public/w3-theme.css'); ?>">
        <title><?php echo $title; ?></title>
    </head>
    <body class="<?php echo COLOR1; ?>">
        <?php if (isset($status)) { ?>
            <div id="status-message" class="w3-modal" v-if="shown" style="display: block">
                <div class="w3-modal-content w3-card-4 w3-animate-top">
                    <span class="w3-button w3-display-topright" v-bind:class="[color]" v-on:click="hide()">&times;</span>
                    <div class="w3-container w3-leftbar" v-bind:class="[color, borderColor]">
                        <h3><span v-html="header"></span></h3>
                        <p><span v-html="message"></span></p>
                        <button class="w3-btn" v-bind:class="[buttonColor]" v-on:click="hide()">TUTUP</button><br>
                        <br>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div id="modal" class="w3-modal" v-if="shown !== undefined" style="display: block">
            <div class="w3-modal-content w3-card-4 w3-animate-top <?php echo COLOR3; ?>">
                <span class="w3-button w3-display-topright" v-on:click="hide()">&times;</span>
                <div class="w3-container">
                    <h3><span v-html="items[shown].header"></span></h3>
                    <p><span v-html="items[shown].content"></span></p>
                    <button class="<?php echo COLOR6; ?> w3-btn" v-on:click="hide()">OK</button><br>
                    <br>
                </div>
            </div>
        </div>
        <script>
            function yaqueen() {
                return confirm('Apakah anda yakin?');
            }

            <?php if (isset($status)) { ?>
                window.statusMessage = new Vue({
                    el: '#status-message',
                    data: {
                        shown: true,
                        color: '<?php echo $status['success'] === TRUE ? 'w3-pale-green' : 'w3-pale-red'; ?>',
                        borderColor: '<?php echo $status['success'] === TRUE ? 'w3-border-green' : 'w3-border-red'; ?>',
                        buttonColor: '<?php echo $status['success'] === TRUE ? 'w3-green' : 'w3-red'; ?>',
                        header: '<?php echo $status['success'] === TRUE ? 'BERHASIL:' : 'GAGAL:'; ?>',
                        message: '<?php echo $status['message']; ?>'
                    },
                    methods: {
                        hide: function() {
                            this.shown = false;
                        }
                    }
                });
            <?php } ?>

            window.modal = new Vue({
                el: '#modal',
                data: {
                    shown: undefined,
                    items: {
                        <?php if (isset($modal_items)) { ?>
                            <?php foreach ($modal_items as $modal_item) { ?>
                                '<?php echo $modal_item['key']; ?>': {
                                    header: '<?php echo $modal_item['header']; ?>',
                                    content: '<?php echo $modal_item['content']; ?>'
                                },
                            <?php } ?>
                        <?php } ?>
                    }
                },
                methods: {
                    show: function(key) {
                        if (this.items[key]) {
                            this.shown = key;
                        }
                    },
                    hide: function() {
                        this.shown = undefined;
                    }
                }
            });
        </script>