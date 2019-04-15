<?php namespace Base10;

if (!function_exists("url")) {
    function url(... $path)
    {
        return Uri\Url::init($path);
    }
}