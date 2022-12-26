<?php


namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Enum;


use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\sl;

class Dedicatedformation extends Enum
{
    const PROFESSIONAL_USE ='professionalUse';
    const IP = 'ip';
    const OS ='os';
    const STATE ='state';
    const SERVER_ID='serverId';
    const BOOT_ID ='bootId';
    const NAME ='name';
    const MONITORING ='monitoring';
    const RACK ='rack';


    static function getKeysAndTranslate(){
        $data = [];
        foreach (self::getConstants() as $key){
            $data[$key] =  sl('lang')->abtr($key);
        }
        return $data;
    }
}