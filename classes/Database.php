<?php


class Database
{
    private static $connection;

    public static function getConnection()
    {
        if (!self::$connection) {
            $config = require __DIR__ . '/config/config.php';
            self::$connection = new mysqli($config['DB_HOST'], $config['DB_USER'], $config['DB_PASS'], $config['DB_NAME']);
        }

        return self::$connection;
    }
}