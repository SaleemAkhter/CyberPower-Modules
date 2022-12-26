<?php

namespace ModulesGarden\WordpressManager\Core\Api;

use \ModulesGarden\WordpressManager\Core\Api\AbstractApi\Curl\Request;
use \ModulesGarden\WordpressManager\Core\DependencyInjection;

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
