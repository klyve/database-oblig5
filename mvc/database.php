<?php

namespace MVC;
require_once('app/config.php');

class Database {
    private static $connection = false;
    public static function instance() {
        global $config;
        if(!self::$connection) {
            self::$connection = new \PDO('mysql:dbname='.$config['database']['database'].';host=' . $config['database']['host'], $config['database']['username'], $config['database']['password'],
            array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
        }
        return self::$connection;
    }
}
