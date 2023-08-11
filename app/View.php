<?php

namespace App;

class View
{


    public static function make($name, array $data = [])
    {
        $file = __VIEW__ . str_replace('.', DIRECTORY_SEPARATOR, $name) . '.php';
        if (!file_exists($file)) {
            throw new \Exception('File not exist');
        }

        extract($data);

        ob_start();
        require_once $file;
        return ob_get_clean();
    }
}