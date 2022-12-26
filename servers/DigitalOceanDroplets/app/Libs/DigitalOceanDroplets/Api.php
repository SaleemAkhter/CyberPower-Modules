<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets;

use DigitalOceanV2\DigitalOceanV2;
use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters\Client;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters\GuzzleClientAdapter;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\Droplet;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\Image;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\Snapshot;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\Region;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\Volume;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\Key;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\Firewall;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\Projects;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\FloatingIp;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\Connection;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api\Slug;

/**
 * Description of DigitalOceanDroplets
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * 
 * @property Connection $connection
 * @property Droplet $droplet
 * @property Image $image
 * @property Snapshot $snapshot
 * @property Region $region
 * @property Volume $volume
 * @property Key $key
 * @property Firewall $firewall
 * @property Projects $projects
 * @property FloatingIp $floatingIp
 * @property Slug $slug
 * 
 */
class Api
{
    /*
     * @var object $params
     */

    protected $params;

    /*
     * @var object $api
     */
    protected $api;

    /**
     * DigitalOceanDroplets Create ClientAdaper
     * 
     * @param array $params
     */
    public function __construct($params)
    {

        $this->params = new Client($params);
    }

    /*
     * Set API Connection details
     * 
     */

    public function setAPI()
    {
        global $CONFIG;
        $whmcsVersion   = (int) explode('.', $CONFIG['Version'])[0];
        $adapter        = $whmcsVersion >= 8 ? new GuzzleClientAdapter($this->params->getApiToken()) : new \DigitalOceanV2\Adapter\GuzzleHttpAdapter($this->params->getApiToken());
        $this->api      = new DigitalOceanV2($adapter);
    }

    /**
     * DigitalOceanDroplets magic method handling
     * 
     * @param string
     * @return object
     */
    public function __get($function)
    {
        if (is_null($function))
        {
            throw new Exception('API: The method cannot be empty.');
        }
        $this->setAPI();
        $className = __NAMESPACE__ . '\Api\\' . ucfirst($function);

        if (class_exists($className))
        {
            return new $className($this->api, $this->params);
        }

        throw new Exception('API: The method does not exist.');
    }

}
