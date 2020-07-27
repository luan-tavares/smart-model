<?php

namespace Luan\SmartModel\Query\Concerns;

use \PDO;
use PDOException;
use \PDOStatement;
use Luan\SmartModel\Model;
use Luan\SmartModel\DBConnection;
use Luan\SmartModel\Collections\Collection;

trait Create
{
    public function save():boolean
    {
    }

    public function create(array $assocData):?int
    {
        if (!$assocData) {
            return null;
        }
        $columns = implode(", ", array_keys($assocData));
        $values = ":".implode(", :", array_keys($assocData));
        $queryText = "INSERT INTO {$this->table}({$columns}) VALUES ({$values})";
        try {
            $statement = $this->executeStatement($queryText, $assocData);
            return DBConnection::get()->lastInsertId();
        } catch (PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    public function update(array $assocData, $fieldKey = "id"):int
    {
        if (!$assocData) {
            return null;
        }
        $fields = $assocData;
        $keyValue = $fields[$fieldKey];
        unset($fields[$fieldKey]);

        $fieldsUpdate = implode(", ", array_map(function ($v) {
            return "{$v} = :{$v}";
        }, array_keys($fields)));

        $fields[$fieldKey] = $keyValue;
        $queryText = "UPDATE {$this->table} SET {$fieldsUpdate} WHERE {$fieldKey} = :{$fieldKey} LIMIT 1";

        try {
            $statement = $this->executeStatement($queryText, $fields);
            return $fields["id"];
        } catch (PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    private function removeNotFillable()
    {
    }
}
