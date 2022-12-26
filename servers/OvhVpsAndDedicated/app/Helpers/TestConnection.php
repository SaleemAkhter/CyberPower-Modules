<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers;


use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\Ovh;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api;
use Exception;

/**
 * Description of TestConnection
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class TestConnection
{
    /*
     * @var array $params
     */

    private $params;


    /**
     * @var Api
     */
    private $api;

    public function __construct($params)
    {
        $this->params = $params;
        $this->api = Ovh::API($this->params);
    }

    /**
     * Test Connection
     * 
     * @return array
     */
    public function testConnection()
    {
        $result = $this->api->me->details();
        if(is_a($result, 'Exception'))
        {
            return ['error' => $result->getMessage()];
        }

        if (!empty($result))
        {
            return [
                'success' => true
            ];
        }
    }

}
