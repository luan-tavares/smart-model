<?php

namespace Luan\SmartModel\Data;

trait Show
{
    public function toArray():?array
    {
        return (array)  $this->data;
    }
}