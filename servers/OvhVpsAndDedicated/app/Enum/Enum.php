<?php


namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Enum;


abstract class Enum
{

    static function getConstants(){
        $class = new \ReflectionClass(get_called_class());
        return $class->getConstants();
    }

    static function getKeys(){
        $class = new \ReflectionClass(get_called_class());
        return array_keys($class->getConstants());
    }


}