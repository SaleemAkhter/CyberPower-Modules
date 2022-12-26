<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;

/**
 * Description of ConfigOptions
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class TestConnection
{
    /*
     * @var array $params
     */

    private $params;

    /*
     * Set params
     * 
     * @param array
     */

    public function __construct($params)
    {
        $this->params = $params;
    }

    /*
     * Test Connection
     * 
     * @return array
     */

    public function testConnection()
    {
        $api            = new Api($this->params);
        $testConnection = $api->connection->test();

        if (isset($testConnection->status))
        {
            return [
                'success' => true
            ];
        }
    }

}
