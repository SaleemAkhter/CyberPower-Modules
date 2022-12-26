<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Abstracts;

use DigitalOceanV2\DigitalOceanV2;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters\Client;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Collection;

/**
 * Description of AbstractApi
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class AbstractApi
{
    /*
     * @var object $api
     */

    protected $api;

    /*
     * @var \ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters\Client 
     */
    protected $client;

    /*
     * Set API, Client object 
     * 
     * @param object $api, $client
     * @return void
     */

    public function __construct(DigitalOceanV2 $api, Client $client = null)
    {
        $this->api    = $api;
        $this->client = $client;
    }

}
