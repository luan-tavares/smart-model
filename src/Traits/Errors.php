<?php

namespace Luan\SmartModel\Traits;

use \PDOException;

trait Errors
{
    private $error;

    private function errorThrow($message)
    {
        throw new PDOException($message);
    }

    private function errorDie()
    {
        var_dump($this);
        die();
    }
}