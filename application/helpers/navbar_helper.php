<?php

function nav_bar() {
    ?><div class="w3-bar w3-card <?php echo COLOR2; ?>"><?php
}

function nav_item($current_section, $target_section, $title, $attributes = '') {
    if ($current_section === $target_section) {
        $color = COLOR3;
    } else {
        $color = '';
    }

    ?><a class="w3-bar-item w3-button w3-mobile <?php echo $color; ?>" href="<?php echo site_url($target_section); ?>" <?php echo $attributes; ?>><?php echo $title; ?></a><?php
}

function nav_dropdown($current_section, $target_section, $title, $attributes = '') {
    if ($current_section === $target_section.strstr($current_section, '_')) {
        $color = COLOR3;
    } else {
        $color = '';
    }

    ?><a class="w3-button w3-mobile <?php echo $color; ?>" <?php echo $attributes; ?>><?php echo $title; ?></a><?php
}

function nav_dropdown_item($current_section, $target_section, $title, $attributes = '') {
    if ($current_section === $target_section) {
        $color = COLOR2;
    } else {
        $color = COLOR3;
    }

    ?><a class="w3-bar-item w3-button w3-mobile <?php echo $color; ?>" href="<?php echo site_url($target_section); ?>" <?php echo $attributes; ?>><?php echo $title; ?></a><?php
}

function sidenav_bar_server() {
    ?>
    <div id="sidenav-bar-server" class="w3-sidebar w3-bar-block w3-card w3-animate-left w3-black" style="opacity: 0.95; width: 300; display: block" v-if="shown">
    <?php
}

function sidenav_bar_client($title) {
    ?>
    <div id="sidenav-bar-client" class="w3-bar w3-card <?php echo COLOR2; ?>">
        <button class="w3-bar-item w3-button" v-on:click="show()">&#9776;</button>
        <span class="w3-bar-item"><b><?php echo $title; ?></b></span>
    </div>
    <script>
    window.sidenavBar = new Vue({
        el: '#sidenav-bar-server',
        data: {
            shown: false
        }
    });
    window.sidenavClient = new Vue({
        el: '#sidenav-bar-client',
        methods: {
            show: function() {
                window.sidenavBar.shown = true;
            }
        }
    });
    </script>
    <?php
}

function sidenav_header() {
    ?>
    <div class="w3-bar-item w3-cell-row">
        <div class="w3-cell w3-cell-middle w3-center w3-text-theme" style="width: 99%">
            <h3>Menu</h3>
        </div>
        <div class="w3-cell w3-cell-middle w3-center">
            <h6><a class="w3-button w3-circle" v-on:click="shown = false">&times;</a></h6>
        </div>
    </div>
    <div style="padding-left: 16px; padding-right: 16px">
        <hr>
    </div>
    <?php
}

function sidenav_item($current_section, $target_section, $title, $require_args = FALSE) {
    if ($require_args ? (strpos($current_section, $target_section) === 0) : ($current_section === $target_section)) {
        $color = COLOR4;
        $bold_prefix = '<b>';
        $bold_postfix = '</b>';
    } else {
        $color = '';
        $bold_prefix = '';
        $bold_postfix = '';
    }

    ?><a class="w3-bar-item w3-button <?php echo $color; ?>" href="<?php echo site_url($target_section); ?>"><?php echo $bold_prefix.$title.$bold_postfix; ?></a><?php
}

?>
