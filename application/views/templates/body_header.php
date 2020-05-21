<header>
    <div id="sidenav-bar-client" class="w3-cell-row w3-padding">
        <div class="w3-cell w3-cell-middle">
            <button class="w3-cell w3-cell-middle w3-btn w3-black w3-card-4 w3-xlarge" v-on:click="show()">&#9776;</button>
        </div>
        <div class="w3-cell w3-cell-middle w3-padding" style="width: 99%">
            <h1>e-Ticketing AKSEN SMAGA</h1>
        </div>
    </div>
</header>
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
<div class="<?php echo COLOR3; ?>">
