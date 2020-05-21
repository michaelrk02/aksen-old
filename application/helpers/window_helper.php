<?php function window_frame($title) { ?>
    <div class="w3-card-4 w3-animate-zoom <?php echo COLOR5; ?>">
        <div class="w3-container <?php echo COLOR4; ?>">
            <h3><?php echo $title; ?></h3>
        </div>
<?php } ?>

<?php function window_tabs_begin() { ?>
    <div class="w3-cell-row <?php echo COLOR4; ?>">
        <div class="w3-cell w3-bar" style="width: 99%">
<?php } ?>

<?php function window_tabs_end($help_id = NULL) { ?>
        </div>
        <?php if (isset($help_id)) { ?>
            <div id="<?php echo $help_id; ?>" class="w3-cell">
                <a class="w3-button w3-circle" v-on:click="showHelp()">?</a>
            </div>
        <?php } ?>
    </div>
<?php } ?>

<?php function window_tab($current_section, $target_section, $title) { ?>
    <?php $color = ($current_section === $target_section) ? COLOR5 : ''; ?>
    <a href="<?php echo site_url($target_section); ?>" class="w3-button <?php echo $color; ?>"><?php echo $title; ?></a>
<?php } ?>
