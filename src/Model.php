<?php

namespace Luan\SmartModel;

use ReflectionClass;
use stdClass;
use Luan\SmartModel\Core\Container;
use Luan\SmartModel\Data\Show;

abstract class Model
{
    use Show;
    
    protected $table;
    
    protected $hidden = [];
    
    protected $fillable = [];

    private $original;
        
    private $data;

    private $message;

    private $error;

    private $final = false;
    
    public function __construct()
    {
        $this->original = (array) $this->data;
    }

    public function __call($method, $parameters)
    {
        return Container::get($this)->{$method}(...$parameters);
    }

    public static function __callStatic($method, $parameters)
    {
        $instance = new static;
        return call_user_func_array([$instance, $method], $parameters);
    }

    public function __set($name, $value)
    {
        if (empty($this->data)) {
            $this->data = new stdClass;
        }
        $this->data->{$name} = $value;
    }

    public function __get($name)
    {
        if (!isset($this->data->{$name})) {
            $this->message = "Model ". static::class .": Propriedade \"{$name}\" não existe!";
            die($this->message);
        }
        return $this->data->{$name};
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __toString()
    {
        return json_encode($this->data);
    }

    public function setPropertyValue($name, $value)
    {
        if (!property_exists($this, $name)) {
            die("oi $name");
        }
        $this->{$name} = $value;
    }

    public function save()
    {
        if (!empty($this->data)) {
        }
    }
}