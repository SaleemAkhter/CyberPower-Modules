<?php


namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Enum;


use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\sl;

class ServiceInformation extends Enum
{
    const NETBOOT_MODE ='netbootMode';
    const STATE ='state';
    const VCORE ='vcore';
    const SLA_MONITORING ='slaMonitoring';
    const IP = 'ip';
    const IP_RESERSE ='ipReverse';
    const MEMORY_LIMIT ='memoryLimit';
    const DISK ='disk';

    static function getKeysAndTranslate(){
        $data = [];
        foreach (self::getConstants() as $key){
            $data[$key] =  sl('lang')->abtr($key);
        }
        return $data;
    }
}