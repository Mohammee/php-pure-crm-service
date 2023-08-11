<?php

namespace App;

/**
 * @mixin \PDO
 */
class DB
{

    private ?\PDO $pdo;
    private static ?DB $db = null;

    private function __construct()
    {
        try {
            $this->pdo = new \PDO('mysql:host=localhost;dbname=task;port=3306', 'root', '', [
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getInstace()
    {
        if (!static::$db) {
            static::$db = new self();
        }

        return static::$db;
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->pdo, $name], $arguments);
    }
}