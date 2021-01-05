<?php

if (!function_exists('returnNumber')) {
    function returnNumber(string $string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }

}