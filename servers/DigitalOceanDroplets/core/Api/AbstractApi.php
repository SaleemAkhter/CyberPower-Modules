<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Api;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\Api\AbstractApi\Curl\Request;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\DependencyInjection;

/**
 * Description of AbstractApi
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class AbstractApi
{

    protected $token;
    protected $code;

    /**
     * @return Request
     */
    protected function getNewRequest()
    {
        return DependencyInjection::create(Request::class);
    }

}
