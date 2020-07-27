<?php


namespace Luan\SmartModel\Query;

use Luan\SmartModel\DBConnection;
use stdClass;
use PDO;
use PDOException;
use PDOStatement;
use ReflectionClass;

class Builder
{
    use \Luan\SmartModel\Query\Concerns\Read;
    use \Luan\SmartModel\Query\Concerns\Create;

    private const MAX_LIMIT = 1000;

    private $connectionModel;

    private $table;

    private $limit = 50;

    private $offset = 0;

    private $where;

    private $from;

    private $fail;

    public function __construct($table)
    {
        $this->table = $table;
        $this->connectionModel = DBConnection::models($table);
        $this->resetAll();
    }

    public function resetAll()
    {
        $this->from = $this->table;
    }
    
    private function executeStatement($query, array $params = []) : ?PDOStatement
    {
        $statement = DBConnection::get()->prepare($query);
        foreach ($params as $key => $value) {
            $type = (is_numeric($value))?(PDO::PARAM_INT):(PDO::PARAM_STR);
            $statement->bindValue(":{$key}", $value, $type);
        }
        $statement->execute();
        return $statement;
    }

    public function limit($limitValue)
    {
        if ($limitValue > self::MAX_LIMIT or $limitValue <= 0) {
            return $this;
        }
        $this->limit = $limitValue;
        return $this;
    }

    public function offset($offsetValue)
    {
        if ($offsetValue <= 0) {
            return $this;
        }
        $this->offset = $offsetValue;
        return $this;
    }
}