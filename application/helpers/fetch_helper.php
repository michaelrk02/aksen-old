<?php

function fetch($query, $n = 0) {
    if ($n === -1) {
        return $query->get()->result_object();
    }

    return $query->get()->row_object($n);
}

?>
