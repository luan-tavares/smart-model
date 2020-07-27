<?php

namespace Luan\SmartModel\Core;

use PDO;
use stdClass;
use PDOException;
use ReflectionClass;
use Luan\SmartModel\DBConnection;
use Luan\SmartModel\Query\Builder as QueryBuilder;

class Builder
{
    private $query;

    private static $queryMethods;

    private $model;

    public function __construct($model)
    {
        $class = new ReflectionClass($model);
       
        $table = $class->getProperty("table");
        $table->setAccessible(true);
        $tableName = mb_strtolower($table->getValue($model));

        DbConnection::models($tableName, get_class($model));

        $this->model = $model;

        if (!self::$queryMethods) {
            self::$queryMethods = get_class_methods(QueryBuilder::class);
        }
        

        $this->query = new QueryBuilder($tableName);
    }

    public function query()
    {
        return $this->query;
    }

    public function __call($method, $parameters)
    {
        $executed = null;
        if (in_array($method, self::$queryMethods)) {
            $executed = $this->query->{$method}(...$parameters);
            if ($executed instanceof QueryBuilder) {
                return $this;
            }
            return $executed;
        }
    }
}