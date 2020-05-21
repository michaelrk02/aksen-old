<?php

date_default_timezone_set('Asia/Jakarta');

function rupiah($n) {
    return sprintf('Rp. %s', number_format($n, 2, ',', '.'));
}

function order_id($n) {
    return sprintf('ORD%05d', $n);
}

?>
