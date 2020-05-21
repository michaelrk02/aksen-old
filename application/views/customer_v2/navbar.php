<?php sidenav_bar_server(); ?>
    <?php sidenav_header(); ?>
    <?php sidenav_item(uri_string(), 'customer_v2', 'Pemesanan'); ?>
    <?php sidenav_item(uri_string(), 'customer_v2/terms', 'Syarat dan Ketentuan'); ?>
    <?php sidenav_item(uri_string(), 'customer_v2/info', 'Informasi Lanjut'); ?>
    <?php sidenav_item(uri_string(), 'customer_v2/help', 'Bantuan', TRUE); ?>
    <?php if (isset($_SESSION['e_ticketing_login'])) { ?>
        <?php sidenav_item(uri_string(), 'customer_v2/logout', 'Keluar'); ?>
    <?php } ?>
</div>
