<?php
namespace Config;


class DBConfig
{
    static $DB_USERNAME = 'root';
    static $DB_PASSWORD = null;
    static $DB_NAME = 'spg';
    static $DB_HOST = null;
    static $DB_PORT = null;

    private static $_dbInstance = null;

    public static function getInstance($options = null) {
        if(static::$_dbInstance)
            return static::$_dbInstance;

        $host     = static::$DB_HOST;
        $dbname   = static::$DB_NAME;
        $port     = static::$DB_PORT;

        $PDO = new \PDO("mysql:host={$host};port={$port};dbname={$dbname}",
            static::$DB_USERNAME,
            static::$DB_PASSWORD,
            $options);

        $PDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        static::$_dbInstance = $PDO;
        return $PDO;
    }


}

include_once __DIR__ .'/../../config.php';