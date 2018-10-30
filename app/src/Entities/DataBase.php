<?php

namespace Entities;

use PDO;
use PDOException;

/**
 * Class DataBase
 * @package Entities
 * This class is used to set all we need to use the Database
 * It is extended by all the repositories to have the availability to use the Database connection
 */
class DataBase {


    /**
     * @var bool
     * used to set which environment we use (local or C9 cloud server)
     */
    private $c9 = false;

    /**
     * @var array
     * example of using for the array:
     * $this->dsn["host"] = 'localhost';
     * $this->dsn["port"] = "3306";
     * $this->dsn["user"] = 'root';
     * $this->dsn["pass"] = 'root';
     * $this->dsn["name"] = 'c9';
     */
    private $dsn = [];

    /**
     * @var null
     * Setted null by default, this variable static will be used to join the db
     */
    protected static $dbConnection = null;

    /**
     * DataBase constructor.
     * configure the connection for the DB by singleton depending the environment
     */
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

    /**
     * Set the variable dsn
     * default initialisation is for localhost db connection.
     */
    private function initDefault() {
        $this->dsn["host"] = 'localhost';
        $this->dsn["port"] = "3306";
        $this->dsn["user"] = 'root';
        $this->dsn["pass"] = 'root';
        $this->dsn["name"] = 'c9';
    }

    /**
     * Set the variable dsn
     * other setting for the dsn when it's not localhost but server
     */
    private function initC9() {
        $this->dsn["host"] = getenv('IP');
        $this->dsn["port"] = "3306";
        $this->dsn["user"] = getenv('C9_USER');
        $this->dsn["pass"] = "";
        $this->dsn["name"] = 'c9';
    }
}