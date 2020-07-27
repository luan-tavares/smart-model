<?php

namespace Luan\SmartModel\Collections;

use PDO;
use stdClass;
use PDOException;
use ReflectionClass;
use Closure;

class Collection
{
    private $items;

    private $itemsArray;

    public function __construct(array $items)
    {
        $this->items = $items;
        $this->itemsArray = $this->toArray();
    }

    public function toArray()
    {
        if (!$this->itemsArray) {
            $this->itemsArray = array_map(function ($v) {
                return $v->toArray();
            }, $this->items);
        }
        return $this->itemsArray;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function all()
    {
        return $this->items;
    }

    public function map(Closure $fn)
    {
        return array_map(function ($v) use ($fn) {
            return $fn($v);
        }, $this->itemsArray);
    }

    public function mapWithKeys(Closure $fn)
    {
        $result = [];
        $firstValidate = false;
        foreach ($this->itemsArray as $value) {
            $call = $fn($value);
            if (!$firstValidate) {
                if (!is_array($call)) {
                    die("Retorno da closure deve ser um array");
                }
                if (count($call) != 1) {
                    die("Array deve ter apenas 1 elemento");
                }
                
                $firstValidate = true;
            }
            if ($result and !array_diff_key($result, $call)) {
                die("Há índice repetido");
            }
            foreach ($call as $key => $v) {
                $result[$key] = $v;
            }
        }

        return $result;
    }
}