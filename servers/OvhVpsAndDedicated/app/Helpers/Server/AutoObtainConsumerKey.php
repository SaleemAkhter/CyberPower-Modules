<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server;

use ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\Ovh;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Providers\Service\Provider;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\HandlerError\Exceptions\Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Server;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\OutputBuffer;

/**
 * Class AutoObtainConsumerKey
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AutoObtainConsumerKey
{
    use RequestObjectHandler;
    use WhmcsParamsApp;
    use OutputBuffer;

    /**
     * @var array
     */
    private $apiConfigData;

    private $serverId;
    private $requestParams;
    /**
     * @var array
     */
    private $callConsumerKeyResponse;

    public function __construct($apiData = [], $serverId = null)
    {
        $this->loadRequestObj();
        $this->apiConfigData = $apiData;
        $this->serverId = $serverId;
        $this->requestParams = $this->request->request->all();

        if(!$this->requestParams['serverid'])
        {
            $this->requestParams['serverid'] = $this->serverId;
        }
    }

    public function run()
    {
        if($this->isConsumerKey())
        {
            return;
        }
        if(!$this->isApiKeyAndSecret())
        {
            return;
        }

        try
        {
            $this->callConsumerKey();
        }
        catch (\Exception $exception)
        {
            //stop
            return;
        }

        Server::where('id', $this->serverId)->update(['accesshash' => $this->callConsumerKeyResponse['consumerKey']]);

        $this->cleanOutputBuffer();
        header("Location: ". $this->callConsumerKeyResponse['validationUrl']);
        die;
    }

    public function callConsumerKey()
    {
        $provider       = new Provider($this->requestParams);
        $api            = Ovh::API($provider->getParams());

        $query          = '?action=manage&id='. $this->serverId;
        $schema         = !empty($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'https://';
        $redirectUrl    = $schema .'://'. $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . $query;

        $this->callConsumerKeyResponse = $api->auth->credential($redirectUrl);
    }

    public function isConsumerKey()
    {
        return trim($this->getRequestValue('accesshash')) !== "";
    }

    public function isApiKeyAndSecret()
    {
        return trim($this->getRequestValue('username')) !== "" && trim($this->getRequestValue('password')) !== "";
    }
}