<?php

function random_id($length) {
    mt_srand();

    $id = '';
    for ($i = 0; $i < $length; $i++) {
        $mode = mt_rand(0, 1);
        if ($mode == 0) {
            $ascii = mt_rand(ord('a'), ord('z'));
        } else {
            $ascii = mt_rand(ord('0'), ord('9'));
        }
        $id .= chr($ascii);
    }

    return $id;
}

?>
