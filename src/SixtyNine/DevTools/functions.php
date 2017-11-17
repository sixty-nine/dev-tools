<?php

if (!function_exists('array_get')) {

    function array_get($key, $array, $default = null) {
        return array_key_exists($key, $array) ? $array[$key] : $default;
    }
}
 
 