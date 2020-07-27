<?php

namespace Luan\SmartModel\Query\Concerns;

use \PDO;
use \PDOStatement;
use Luan\SmartModel\Model;
use Luan\SmartModel\Collections\Collection;

trait Read
{
    public function get(array $listFields = ["*"]):?Collection
    {
        $fields = $this->fields($listFields);
        $query = "select {$this->from}.{$fields} from {$this->from} LIMIT {$this->limit}";
        $statement = $this->executeStatement($query);
        $items = $statement->fetchAll(PDO::FETCH_CLASS, $this->connectionModel);
        if (!$items) {
            return null;
        }
        return new Collection($items);
    }

    public function first(array $listFields = ["*"]):?Model
    {
        $fields = $this->fields($listFields);
        $query = "select {$fields} from {$this->from} LIMIT 1";
        $statement = $this->executeStatement($query);
        $statement->setFetchMode(PDO::FETCH_CLASS, $this->connectionModel);
        if (!($item = $statement->fetch())) {
            return (new $this->connectionModel);
        }
        return $item;
    }

    private function fields($fields) : ?string
    {
        return implode(",", $fields);
    }
}
