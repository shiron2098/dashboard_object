<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . "/Interface/connectToDB.php";

use app\connectToDB;
class MYSQLConnect implements connectToDB
{

    private static $pdo = [];

    protected function __clone()
    {
    }

    protected function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    protected function __construct()
    {
    }

    public static function ConnectToDB($host, $username, $password, $database)
    {
        try {
            if (!empty($host) && !empty($username) && !empty($password) && !empty($database)) {
                $dsn = "mysql:host=" . $host . ";dbname=" . $database . ";";
                $opt = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                self::$pdo = new PDO($dsn, $username, $password, $opt);
                return self::$pdo;
            } else {
                trigger_error('constant not found');
            }
        } catch (PDOException $e) {
            print_r($e->getMessage());
            die();
        }
    }
}
