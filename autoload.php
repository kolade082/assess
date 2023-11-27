<?php
function autoload($className)
{
    $fileName = str_replace('\\', '/', $className) . '.php';
    $file = __DIR__ . '/'. $fileName;

    if (file_exists($file)) {
        require $file;
    }
}

spl_autoload_register('autoload');





