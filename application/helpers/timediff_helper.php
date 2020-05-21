<?php

function time_diff($time1, $time2, $units = 1) {
    return floor(abs($time1 - $time2) / $units) + 1;
}

function seconds_diff($time1, $time2) {
    return time_diff($time1, $time2, 1);
}

function minutes_diff($time1, $time2) {
    return time_diff($time1, $time2, 1 * 60);
}

function hours_diff($time1, $time2) {
    return time_diff($time1, $time2, 1 * 60 * 60);
}

function days_diff($time1, $time2) {
    return time_diff($time1, $time2, 1 * 60 * 60 * 24);
}

?>
