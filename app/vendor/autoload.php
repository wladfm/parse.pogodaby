<?php

namespace Vendor;

use Exception;

Autoload::setup();

class Autoload
{
    public static function setup()
    {
        spl_autoload_register(__NAMESPACE__ . '\Autoload::load');
    }

    /**
     * @throws Exception
     */
    public static function load(string $className)
    {
        $file = __DIR__ . DIRECTORY_SEPARATOR . strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $className)) . '.php';
        if (file_exists($file)) {
            require $file;
        } else {
            throw new Exception('Class ' . $className . ' not found');
        }
    }
}