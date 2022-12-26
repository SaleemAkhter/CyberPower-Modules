<?php

namespace ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps;

use \LKDev\HetznerCloud\HetznerAPIClient;
use Guzzle\Http\Exception\RequestException;
use LKDev\HetznerCloud\Models\Images\Images;
use LKDev\HetznerCloud\Models\Servers\Servers;
use LKDev\HetznerCloud\Models\Firewalls\Firewalls;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Adapters\Client;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api\Repositories\BackupsRepository;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api\Repositories\SnapshotRepository;

/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 25.03.2019
 * Time: 10:27
 *
 * @method Servers servers()
 * @method volumes()
 * @method actions()
 * @method serverTypes()
 * @method datacenters()
 * @method locations()
 * @method Images images()
 * @method sshKeys()
 * @method prices()
 * @method isos()
 * @method floatingIps()
 * @method Firewalls firewalls()
 */
class Api
{
    protected $params;
    /**
     * @var HetznerAPIClient
     */
    protected $api;

    /**
     * @var Client $client ;
     */
    protected $client;

    public function __construct($params)
    {
        $this->params = $params;
        $this->createClient();
        $this->connect();
    }

    protected function createClient()
    {
        $this->client = new Client($this->params);
    }

    protected function connect()
    {
        $this->api = new HetznerAPIClient($this->client->getApiToken());
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    public function server($serverId)
    {
        return new \LKDev\HetznerCloud\Models\Servers\Server($serverId);
    }

    public function __call($function, $name = null)
    {

        if (method_exists($this->api, $function)) {
            try {
                return $this->api->{$function}();
            } catch (RequestException $ex) {
                $this->handleError($ex->getResponse());
            }
        }

        throw new \Exception('Called method does not exists!');
    }

    protected function handleError($response)
    {
        $body = (string)$response->getBody(true);
        $code = (int)$response->getStatusCode();

        $content = json_decode($body);

        throw new \Exception(isset($content->message) ? $content->message : 'Request not processed.', $code);
    }

    public function snapshots()
    {
        return new SnapshotRepository($this->api->getHttpClient());
    }

    public function backups()
    {
        return new BackupsRepository($this->api->getHttpClient());
    }

}