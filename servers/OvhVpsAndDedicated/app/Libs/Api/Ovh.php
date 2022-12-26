<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Connections\Ovh\Connection as OvhConnection;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;

/**
 * Class Ovh
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Ovh
{
    /**
     * @var Api
     */
    public static $api;

    public function __construct($params)
    {
        if($params instanceof Client)
        {
            $client = $params;
            $this->setAPI($client);
            return;
        }

        if(!$params || empty($params))
        {
            throw new \Exception('Unable to create connection with Ovh');
        }
        $client     = new Client($params);
        $this->setAPI($client);
    }

    /**
     * @param bool $params
     * @return Api
     * @throws \Exception
     */
    public static function API($params = false)
    {
        if (self::$api)
        {
            return self::$api;
        }
        new self($params);
        return self::$api;
    }

    private function setAPI($client)
    {
        $strategyConnection = new StrategyConnection($client);
        $connection = $strategyConnection->getConnection();


        self::$api  = new Api($client, $connection);
    }


}