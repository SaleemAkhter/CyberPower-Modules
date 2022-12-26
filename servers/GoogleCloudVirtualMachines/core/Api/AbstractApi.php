<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Api;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Api\AbstractApi\Curl\Request;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\DependencyInjection;

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
