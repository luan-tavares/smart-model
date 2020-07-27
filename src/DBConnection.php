<?php

namespace Luan\SmartModel;

use PDO;
use PDOException;

abstract class DBConnection
{
    private const OPTIONS = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::MYSQL_ATTR_FOUND_ROWS=> true,
    ];
    
    private static $instance;

    private static $models;

    public static function connect($host, $user, $pass, $database):void
    {
        try {
            $queryDb = "mysql:host={$host};dbname={$database};";
            $options = [];

            self::$instance = new PDO($queryDb, $user, $pass, self::OPTIONS);

            //$conn->setAttribute(PDO::MYSQL_ATTR_FOUND_ROWS, true);
                //$conn->exec("set names utf8");
        } catch (PDOException $e) {
            $text = utf8_encode($e->getMessage());
            die($e->getMessage());
        }
    }

    /**
     * @return PDO
     */
    public static function get() : PDO
    {
        if (empty(self::$instance)) {
            die("Não há conexão com o banco de dados");
        }
        return self::$instance;
    }

    public static function models($table, $model = null)
    {
        if (isset(self::$models[$table])) {
            return self::$models[$table];
        }
        if (!$model) {
            return null;
        }
        self::$models[$table] = $model;
        return self::$models[$table];
    }
}