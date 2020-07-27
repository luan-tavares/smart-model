<?php
namespace Luan\SmartModel\Core;

use Luan\SmartModel\Model;
use \Luan\SmartModel\Core\Builder;

abstract class Container
{
    public static $builderContainer = [];

    public static function get(Model $object):?Builder
    {
        $hash = spl_object_hash($object);
        if (!isset(self::$builderContainer[$hash])) {
            self::$builderContainer[$hash] = new Builder($object);
        }
        return self::$builderContainer[$hash];
    }
}