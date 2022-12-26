<?php

namespace ModulesGarden\Servers\AwsEc2\Core\Api;

use \ModulesGarden\Servers\AwsEc2\Core\Api\AbstractApi\Curl\Request;
use \ModulesGarden\Servers\AwsEc2\Core\DependencyInjection;

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
