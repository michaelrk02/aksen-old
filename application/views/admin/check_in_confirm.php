<div id="landing" class="w3-content" style="max-width: 640px">
    <?php window_frame($title); ?>
        <?php window_tabs_begin(); ?>
            <?php window_tab($section, 'admin/check_in_confirm', 'Konfirmasi check-in'); ?>
        <?php window_tabs_end(); ?>
        <br>
        <div class="w3-container" id="check-in-confirm-page">
            <?php if (!$done) { ?>
                <?php if (isset($account)) { ?>
                    <h3>Check-in</h3>
                    E-mail: <b><?php echo $account->email; ?></b><br>
                    Jumlah check-in: <b><?php echo $account->check_ins; ?>/<?php echo $account->tickets; ?></b>
                    <?php if ($account->check_ins < $account->tickets) { ?>
                        &raquo; <b><?php echo $account->check_ins + 1; ?>/<?php echo $account->tickets; ?></b><br>
                    <?php } ?>
                    <br>
                    <?php if (($account->tickets > 0) && ($account->check_ins < $account->tickets)) { ?>
                        <a href="<?php echo site_url('admin/check_in_confirm/'.$account->id); ?>" class="w3-btn <?php echo COLOR6; ?>">Check-in (+1)</a><br>
                    <?php } else { ?>
                        <i>(tidak dapat check-in)</i><br>
                    <?php } ?>
                    <br>
                <?php } else { ?>
                    <h3>(AKUN TIDAK DITEMUKAN)</h3>
                <?php } ?>
            <?php } else { ?>
                <h3>Proses check-in selesai. Silakan tutup laman ini</h3>
            <?php } ?>
        </div>
    </div>
</div>
