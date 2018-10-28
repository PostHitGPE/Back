<?php

namespace Entities;

use PDO;
use PDOException;

class DataBase {

    //pass $c9 to false to get default local db parameters loaded.
    //pass $c9 to true to get c9 parameters loaded.
    private $c9 = true;
    private $dsn = [];
    protected static $dbConnection = null;

    public function __construct()
    {
        ($this->c9) ? $this->initC9() : $this->initDefault();
        if (empty(self::$dbConnection)) {
            $mysql_connect_str = "mysql:host={$this->dsn["host"]};port={$this->dsn["port"]};dbname={$this->dsn["name"]}";
            try {
                self::$dbConnection = new PDO($mysql_connect_str, $this->dsn["user"], $this->dsn["pass"], [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
                self::$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $exception) {
                self::$dbConnection = null;
                echo "An error occurred, the database was not able to connect\n";
                throw new PDOException($exception->getMessage(), (int)$exception->getCode());
            }
        }
    }

    // return local db parameters
    private function initDefault() {
        $this->dsn["host"] = 'localhost';
        $this->dsn["port"] = "3306";
        $this->dsn["user"] = 'root';
        $this->dsn["pass"] = 'root';
        $this->dsn["name"] = 'posthit';
    }

    // return c9 db paramaters
    private function initC9() {
        $this->dsn["host"] = getenv('IP');
        $this->dsn["port"] = "3306";
        $this->dsn["user"] = getenv('C9_USER');
        $this->dsn["pass"] = "";
        $this->dsn["name"] = 'c9';
    }
}