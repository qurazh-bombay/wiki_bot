<?php
declare(strict_types=1);

if (!function_exists('dump')) {
    function dump($value) {
        echo '<pre>';
        var_dump($value);
        echo '</pre>';
    }
}

if (!function_exists('dd')) {
    function dd($value) {
        dump($value);
        die();
    }
}

if (!function_exists('cal_days_in_month')) {
    function cal_days_in_month(int $calendar, int $month, int $year) {
        return (int) date('t', mktime(0, 0, 0, $month, 1, $year));
    }
}
