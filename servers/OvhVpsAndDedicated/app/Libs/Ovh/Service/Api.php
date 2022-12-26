<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Connections\Ovh\Connection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Auth;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Me;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Order;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Vps;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Ip;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Dedicated;
use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\ClassAction;

/**
 * Description of Ovh
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 *
 * @property Vps $vps
 * @property Me $me
 * @property Order $order
 * @property Dedicated $dedicated
 * @property Ip $ip
 * @property Auth $auth
 */
class Api
{

    /**
     * @var Client
     */
    protected $client;

    protected $api;

    /**
     * Api constructor.
     * @param $params
     */
    public function __construct(Client $client, Connection $ovh)
    {
        $this->client = $client;
        $this->api    = $ovh->getConnection();
    }

    /**
     * @param $function
     * @return mixed
     * @throws Exception
     */
    public function __get($function)
    {
        if (is_null($function))
        {
            throw new Exception('API: The method cannot be empty.');
        }
        $className = __NAMESPACE__ . '\\' . ClassAction::getClassName($this) . '\\' . ucfirst($function);

        if (class_exists($className))
        {

            return new $className($this->api, $this->client, $function);
        }

        throw new Exception('API: The method does not exist.');
    }

    public function getClient()
    {
        return $this->client;
    }
}
