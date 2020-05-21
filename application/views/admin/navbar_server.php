<?php sidenav_bar_server(); ?>
    <?php sidenav_header(); ?>
    <?php if (isset($auth)) { ?>
        <?php sidenav_item($section, 'admin/dashboard', 'Dashboard'); ?>
        <?php sidenav_item($section, 'admin/howto', 'Panduan'); ?>
        <?php sidenav_item($section, 'admin/settings', 'Pengaturan'); ?>
        <?php sidenav_item($section, 'admin/orders', 'Daftar Pesanan'); ?>
        <?php sidenav_item($section, 'admin/accounts', 'Daftar Pembeli'); ?>
        <?php sidenav_item($section, 'admin/check_in', 'Check-in Pengunjung'); ?>
        <?php sidenav_item($section, 'admin/exit', 'Keluar'); ?>
    <?php } else { ?>
        <?php sidenav_item($section, 'admin/auth', 'Masuk'); ?>
    <?php } ?>
</div>
