<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Abstracts;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use Ovh\Api;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Path;
use \Exception;

/**
 * Description of AbstractApi
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 *
 * @method  get($path    = false, $params = false)
 * @method  post($path   = false, $params = false)
 * @method  put( $path   = false, $params = false)
 * @method  delete($path = false, $params = false)
 */
abstract class AbstractApi
{

    /**
     * @var string
     */
    protected $path;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Api
     */
    protected $api;

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * AbstractApi constructor.
     * @param Api $api
     * @param Client $client
     */
    public function __construct(Api $api, Client $client, $path = '')
    {
        $this->api    = $api;
        $this->client = $client;
        $this->path   = Path::createPath($path);
    }

    public function __call($name, $arguments = [])
    {
        if(!method_exists($this->api, $name))
        {
            \logModuleCall('Fatal error: Ovh Unsupported api method', 'API: '. $name, [], var_export($arguments, true));
            throw new Exception('Unsupported api method');
        }

        $path = !$arguments[0] ? $this->path : $this->getPathExpanded($arguments[0]);
        try
        {
            $return = $this->api->{$name}($path, $arguments[1]);
        }
        catch (Exception $exception)
        {
            $exceptionParams = [
                  'message' => $exception->getMessage(),
                  'code' => $exception->getCode(),
                  'file' => $exception->getFile(),
                  'line' => $exception->getLine(),
            ];
            \logModuleCall('Fatal error: Ovh', 'API: '. $name, [$path, $arguments[1]], $exceptionParams);

            throw new Exception($this->getJSONMessageFromException($exception->getMessage()), $exception->getCode());
        }

        $response = var_export($return, true);
        $response = is_string($response) ? [$response] : [];
        \logModuleCall('Ovh', "API: $name", var_export([$path, $arguments[1]], true), $response);


        return $return;
    }

    /**
     * @param $expander
     * @return string
     */
    protected function getPathExpanded($expander)
    {
        return Path::expandPath($this->path, $expander);
    }

    private function getJSONMessageFromException($message)
    {
        preg_match('/\{(.*?)}/', $message, $json);

        $response = "{$json[0]}";
        $jsonDecoded = \json_decode($response);

        return trim($jsonDecoded->message) !== '' ? $jsonDecoded->message : $message;
    }

    public function requestCredentials(array $accessRules, $redirection = null)
    {
        return $this->api->requestCredentials($accessRules, $redirection);
    }
}
