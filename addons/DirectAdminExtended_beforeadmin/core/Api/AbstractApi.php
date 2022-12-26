<?php

namespace ModulesGarden\DirectAdminExtended\Core\Api;

use \ModulesGarden\DirectAdminExtended\Core\Api\AbstractApi\Curl\Request;
use \ModulesGarden\DirectAdminExtended\Core\DependencyInjection;

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
