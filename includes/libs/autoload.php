<?php

spl_autoload_register(function ($class) {

    $prefix = 'WPRescueTime\\';
    if (0 !== strpos($class, $prefix)) {
        return;
    }

    $file = __DIR__
        .DIRECTORY_SEPARATOR
        .str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix)))
        .'.php';
    if (!is_readable($file)) {
        return;
    }

    require $file;
});
