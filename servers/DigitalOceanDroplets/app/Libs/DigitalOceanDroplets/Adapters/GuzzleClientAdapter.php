<?php


namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters;


use DigitalOceanV2\Adapter\GuzzleHttpAdapter;
use GuzzleHttp\ClientInterface;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\ResponseFactory;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;

class GuzzleClientAdapter extends GuzzleHttpAdapter
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * @param $token
     * @param ClientInterface|null $client
     * @param ResponseFactory|null $responseFactory
     */
    public function __construct($token, ClientInterface $client = null, ResponseFactory $responseFactory = null)
    {
        $this->client = $client ?: new GuzzleClient(['headers' =>['Authorization' => sprintf('Bearer %s', $token)]]);
        $this->responseFactory = $responseFactory ?: MessageFactoryDiscovery::find();
    }

    /**
     * @param string $url
     * @param string|null $content
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $url, $content = '')
    {
        $options = [];

        $options[is_array($content) ? 'json' : 'body'] = $content;
        try {
            $this->response = $this->client->request('DELETE', $url, $options);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }

        return (string)$this->response->getBody();
    }


}