<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated\IP;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated\BaseDedicatedRepository;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\HandlerError\Exceptions\Exception;


/**
 * Class Repository
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Repository extends BaseDedicatedRepository
{

    public function getAllToModel()
    {
        return $this->server->ips()->allToModel();
    }

    public function getAllToArray()
    {
        return $this->server->ips()->toArray();
    }

    public function findReverseForIp($ip)
    {

        if(filter_var($ip['ipAddress'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
        {
            return '-';
        }
        try
        {
            $response =  $this->api->ip->one($ip['fullIp'])->reverse()->one($ip['ipAddress']);
        }
        catch (\Exception $exception)
        {
            return '-';
        }


        return isset($response['reverse']) ? $response['reverse'] : '-';
    }

}