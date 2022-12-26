<?php


namespace ModulesGarden\Servers\VultrVps\App\Api;

use GuzzleHttp\Client;
use ModulesGarden\Servers\VultrVps\App\Api\Models\Account;
use ModulesGarden\Servers\VultrVps\App\Api\Models\FirewallGroup;
use ModulesGarden\Servers\VultrVps\App\Api\Models\Instance;
use ModulesGarden\Servers\VultrVps\App\Api\Models\Region;
use ModulesGarden\Servers\VultrVps\App\Api\Models\Tag;
use ModulesGarden\Servers\VultrVps\App\Api\Repositories\OsRepository;

class ApiClient
{
    const API_URL       = 'https://api.vultr.com/v2/';
    const TAGS_URL           = 'https://tags.global-search-tagging.cloud.ibm.com/v3';
    const RESOURCE_GROUP_URL = 'https://resource-controller.cloud.ibm.com/v2/';
    const GRANT_TYPE         = 'urn:ibm:params:oauth:grant-type:apikey';
    const API_VERSION        = 'v1';
    protected $host;
    protected $protocol = 'https';
    protected $apiKey;
    /**
     * @var Account
     */
    protected $token;
    protected $client;
    protected $responseParser;
    protected $response;
    protected $lastResponse;
    protected $jsonMapper;
    protected $version = '2019-12-03';
    protected $generation = 2;

    /**
     * ApiClient constructor.
     * @param $host
     * @param string $protocol
     */
    public function __construct($host, $apiKey, $protocol = 'https')
    {
        $this->host           = $host;
        $this->apiKey         = $apiKey;
        $this->protocol       = $protocol;
        $this->client         = new Client([
            'defaults' => [
                'exceptions' => false,
            ],
            "http_errors" => false
        ]);
        $this->jsonMapper     = new \JsonMapper();
        $this->responseParser = new ResponseParser();
    }


    /**
     * @return \JsonMapper
     */
    public function getJsonMapper()
    {
        return $this->jsonMapper;
    }

    /**
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }
    protected function getRequestHeaders(){
        return [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
                "Authorization" => sprintf('Bearer %s',$this->apiKey)
            ],
        ];
    }

    public function account()
    {
        $this->response = $this->client->get(self::API_URL.__FUNCTION__, $this->getRequestHeaders());
        $this->responseParser->validate($this->response);
        return $this->jsonMapper->map($this->responseParser->getJson()->account, new Account());
    }


    /**
     * @param $instanceId
     * @return Instance
     */
    public function instance($instanceId = null)
    {
        $entity = (new Instance())->setApiClient($this);
        if(!is_null($instanceId)){
            $entity->setId($instanceId);
        }
        return $entity;
    }

    public function post($uri, $post=[])
    {
        $this->response = $this->client->post(self::API_URL.$uri, array_merge($this->getRequestHeaders(),['json'    => $post])        );

        $this->responseParser->validate($this->response);
        return $this->lastResponse = $this->responseParser->getJson();
    }

    public function put($uri, $post = [])
    {
        $this->response = $this->client->put(self::API_URL.$uri, array_merge($this->getRequestHeaders(),['json'    => $post])        );
        $this->responseParser->validate($this->response);
        return $this->lastResponse = $this->responseParser->getJson();
    }

    public function delete($uri)
    {
        $this->response = $this->client->delete(self::API_URL.$uri, $this->getRequestHeaders());
        $this->responseParser->validate($this->response);
        return $this->lastResponse = $this->responseParser->getJson();
    }

    public function get($uri)
    {
        $this->response = $this->client->get(self::API_URL.$uri,  $this->getRequestHeaders());
        $this->responseParser->validate($this->response);
        return $this->lastResponse = $this->responseParser->getJson();
    }

    public function patch($uri, $post=[])
    {
        $this->response = $this->client->patch(self::API_URL.$uri, array_merge($this->getRequestHeaders(),['json'    => $post])        );
        $this->responseParser->validate($this->response);
        return $this->lastResponse = $this->responseParser->getJson();
    }

    /**
     * @return Region[]
     * @throws \JsonMapper_Exception
     */
    public function regions()
    {
        $response = $this->get(__FUNCTION__);
        $enteries = [];

        foreach ($response->regions as $entity)
        {
            $enteries[] = $this->jsonMapper->map($entity, (new Region())->setApiClient($this));
        }
        return $enteries;
    }

    public function plans(){
        return $this->get( __FUNCTION__)->plans;
    }

    /**
     * @return OsRepository
     */
    public function os(){
        return new OsRepository($this);
    }

    public function iso(){
        return $this->get( __FUNCTION__)->isos;
    }

    public function snapshots(){
        return $this->get( __FUNCTION__)->snapshots;
    }

    public function firewallGroup($id = null)
    {
        $entity = (new FirewallGroup())->setApiClient($this);
        if(!is_null($id)){
            $entity->setId($id);
        }
        return $entity;
    }

    public function applications(){
        return $this->get( __FUNCTION__)->applications;
    }
}