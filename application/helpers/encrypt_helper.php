<?php

function r5r_transform_0($input) {
    $output = '';
    $length = strlen($input);

    for ($i = 0; $i < $length; $i++ ){
        $src = $i;
        $dst = ($i * 2) - ((($i * 2) >= $length) ? (($length % 2 == 0) ? ($length - 1) : $length) : 0);
        $output[$dst] = $input[$src];
    }

    return $output;
}

function r5r_transform_1($input) {
    $output = '';
    $length = strlen($input);

    for ($i = 0; $i < $length; $i++ ){
        $src = ($i * 2) - ((($i * 2) >= $length) ? (($length % 2 == 0) ? ($length - 1) : $length) : 0);
        $dst = $i;
        $output[$dst] = $input[$src];
    }

    return $output;
}

function r5r_transform_2($input) {
    $output = '';
    $length = strlen($input);

    for ($i = 0; $i < $length; $i++ ){
        $src = $i;
        $dst = $length - $i - 1;
        $output[$dst] = $input[$src];
    }

    return $output;
}

function r5r_transform_3($input) {
    $output = '';
    $length = strlen($input);

    for ($i = 0; $i < $length; $i++) {
        $chr_old = ord($input[$i]);
        $chr_new = $chr_old;

        if (($chr_old >= ord('A')) && ($chr_old <= ord('Z'))) {
            $chr_new = $chr_old + 32;
        } elseif (($chr_old >= ord('a')) && ($chr_old <= ord('z'))) {
            $chr_new = $chr_old - 32;
        }
        $output[$i] = chr($chr_new);
    }

    return $output;
}

function r5r_transform_4($input) {
    $offset = 5;
    $output = '';
    $length = strlen($input);

    for ($i = 0; $i < $length; $i++) {
        $chr_old = ord($input[$i]);
        $chr_new = $chr_old;

        if (($chr_old >= ord('A')) && ($chr_old <= ord('Z'))) {
            $chr_new = $chr_new - ord('A');
            $chr_new = (26 + $chr_new + ($offset % 26)) % 26;
            $chr_new = $chr_new + ord('A');
        } elseif (($chr_old >= ord('a')) && ($chr_old <= ord('z'))) {
            $chr_new = $chr_new - ord('a');
            $chr_new = (26 + $chr_new + ($offset % 26)) % 26;
            $chr_new = $chr_new + ord('a');
        }
        $output[$i] = chr($chr_new);
    }

    return $output;
}

function r5r_transform_5($input) {
    $offset = -5;
    $output = '';
    $length = strlen($input);

    for ($i = 0; $i < $length; $i++) {
        $chr_old = ord($input[$i]);
        $chr_new = $chr_old;

        if (($chr_old >= ord('A')) && ($chr_old <= ord('Z'))) {
            $chr_new = $chr_new - ord('A');
            $chr_new = (26 + $chr_new + ($offset % 26)) % 26;
            $chr_new = $chr_new + ord('A');
        } elseif (($chr_old >= ord('a')) && ($chr_old <= ord('z'))) {
            $chr_new = $chr_new - ord('a');
            $chr_new = (26 + $chr_new + ($offset % 26)) % 26;
            $chr_new = $chr_new + ord('a');
        }
        $output[$i] = chr($chr_new);
    }

    return $output;
}

function r5r_encrypt($input) {
    $output = $input;

    $output = r5r_transform_0($output);
    $output = r5r_transform_2($output);
    $output = r5r_transform_0($output);
    $output = r5r_transform_0($output);
    $output = r5r_transform_0($output);
    $output = r5r_transform_0($output);
    $output = r5r_transform_0($output);
    $output = r5r_transform_2($output);

    $output = r5r_transform_0($output);
    $output = r5r_transform_2($output);
    $output = r5r_transform_3($output);
    $output = r5r_transform_4($output);

    return $output;
}

function r5r_decrypt($input) {
    $output = $input;

    $output = r5r_transform_5($output);
    $output = r5r_transform_3($output);
    $output = r5r_transform_2($output);
    $output = r5r_transform_1($output);

    $output = r5r_transform_2($output);
    $output = r5r_transform_1($output);
    $output = r5r_transform_1($output);
    $output = r5r_transform_1($output);
    $output = r5r_transform_1($output);
    $output = r5r_transform_1($output);
    $output = r5r_transform_2($output);
    $output = r5r_transform_1($output);

    return $output;
}

?>
