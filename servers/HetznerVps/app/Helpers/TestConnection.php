<?php

namespace ModulesGarden\Servers\HetznerVps\App\Helpers;

use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;

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
        $testConnection = $api->locations()->all();

        if (is_array($testConnection) && isset(reset($testConnection)->name))
        {
            return [
                'success' => true
            ];
        }
    }

}
