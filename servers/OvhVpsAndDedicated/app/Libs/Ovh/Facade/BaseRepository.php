<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\Exception\ApiException;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\Ovh;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Providers\Service\Provider;
use GuzzleHttpOvh\Exception\ClientException;
use GuzzleHttpOvh\Exception\ServerException;


/**
 * Class Base
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
abstract class BaseRepository
{
    protected $params;

    protected $methods;

    protected $item;

    /**
     * @var Api
     */
    protected $api;


    public function __construct($params = [])
    {

        $this->setParams($params);

        $this->api = Ovh::API($this->params);
    }

    protected function setParams($params)
    {

        $service = new Provider($params);
        $this->params = $service->getParams();
    }

    public function __call($name, $arguments)
    {
        if(!in_array($name, $this->methods))
        {
            throw new \Exception("The method ({$name}) does not exist!");
        }

        return $this->item->$name($arguments[0]);
    }

    /**
     * @return Api
     */
    public function getApi()
    {
        return $this->api;
    }

}
