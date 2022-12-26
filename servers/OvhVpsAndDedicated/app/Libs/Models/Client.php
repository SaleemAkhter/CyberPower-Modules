<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Models\Product;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\Constants;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\ServerSettingsManage;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Serializer;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Whmcs\Hosting;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\sl;
use \ModulesGarden\OvhVpsAndDedicated\Core\Models\Whmcs\Client as ClientModel;
use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class Client
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Client extends Serializer
{
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\WhmcsParams;

    private $apiToken;
    private $apiUser;
    private $apiConsumer;
    private $url;

    private $domain;
    private $productID;
    private $hostingID;
    private $whmcsServerID;
    private $serviceName;
    private $country;
    private $ovhSubsidiary;
    private $userid;

    private $action;
    private $serverType;


    private $params;

    const SERVICE_CUSTOM_FIELD_NAME = 'serverName|Server Name';

    private $productConfig;


    public function __construct($params)
    {
        $this->params = $params;
        if ($params['packageid'])
        {
            $this->setProductConfigByPackageId($params['packageid']);

        }

        $this->whmcsServerID = $params['serverid'];
        $this->apiToken      = $params['serverpassword'];
        $this->apiUser       = $params['serverusername'];
        $this->apiConsumer   = $params['serveraccesshash'];

        $this->setConnectionParams();

        $this->domain = $params['domain'];

        $this->action = $params['action'];


        $this->userid      = $params['userid'];
        $this->productID   = $params['packageid'];
        $this->hostingID   = $params['serviceid'];
        $this->serviceName = $params['customfields']['serverName'];
        $this->country     = isset($params['clientsdetails']['countrycode']) ? $params['clientsdetails']['countrycode'] : ServerSettingsManage::getValueIfSetting($this->whmcsServerID, Constants::OVH_SUBSIDIARY);

        $this->setRequestValues();
    }

    private function setRequestValues()
    {

        $request       = sl('request');

        $endpoint = $request->get(Constants::ENDPOINT);

        $this->url           = isset($endpoint) ? $endpoint : ServerSettingsManage::getValueIfSetting($this->whmcsServerID, Constants::ENDPOINT);
        $this->ovhSubsidiary = ServerSettingsManage::getValueIfSetting($this->whmcsServerID, Constants::OVH_SUBSIDIARY);
        $this->serverType    = ServerSettingsManage::getValueIfSetting($this->whmcsServerID, Constants::OVH_SERVER_TYPE);
    }

    private function setProductConfigByPackageId($packageId)
    {
        $serverType = Product::getServerTypeById($packageId);
        if ($serverType == 'vps')
        {
            $this->productConfig = new ProductConfig($this->params);
        }
        elseif ($serverType == 'dedicated')
        {
            $this->productConfig = new DedicatedProductConfig($this->params);
        }
    }

    private function setConnectionParams()
    {
        if ($this->isConnectionParams())
        {
            return;
        }

        if (!$this->whmcsServerID)
        {
            return;
        }

        $data = \ModulesGarden\OvhVpsAndDedicated\App\Models\Whmcs\Server::where('id', $this->whmcsServerID)->first();
        if (!$data)
        {
            return;
        }

        $this->apiToken    = \decrypt($data->password);
        $this->apiUser     = $data->username;
        $this->apiConsumer = $data->accesshash;

    }

    private function isConnectionParams()
    {
        if (!$this->getApiConsumer() || !$this->getApiToken() || !$this->getApiUser())
        {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }


    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return mixed
     */
    public function getServerType()
    {
        return $this->serverType;
    }

    /**
     * @param mixed $serverType
     */
    public function setServerType($serverType)
    {
        $this->serverType = $serverType;
    }

    public function getFullUserNameWithAnchor()
    {
        if(!$this->userid)
        {
            return;
        }
        $client = ClientModel::where('id', $this->userid)->first();

        return sprintf('<a href="clientsservices.php?userid=%s">#%s %s %s</a>',
            $client->id,
            $client->id,
            $client->getFirstnameAttribute(),
            $client->getLastnameAttribute());
    }

    public function getServiceWithAnchor()
    {
        if(!$this->hostingID)
        {
            return;
        }
        $hosting = Hosting::where('id', $this->hostingID)->first();

        return sprintf('<a href="clientsservices.php?userid=%s&productselect=%s">#%s %s</a>',
            $hosting->userid,
            $hosting->id,
            $hosting->id,
            $hosting->domain);
    }

    /**
     * @return mixed
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @param mixed $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }


    /**
     * @return ProductConfig
     */
    public function getProductConfig()
    {
        return $this->productConfig;
    }

    /**
     * @return DedicatedProductConfig
     */
    public function getDedicatedProductConfig()
    {
        return $this->productConfig;
    }

    /**
     * @param ProductConfig $productConfig
     */
    public function setProductConfig($productConfig)
    {
        $this->productConfig = $productConfig;
    }


    /**
     * @return mixed
     */
    public function getOvhSubsidiary()
    {
        return $this->ovhSubsidiary;
    }

    /**
     * @param mixed $ovhSubsidiary
     */
    public function setOvhSubsidiary($ovhSubsidiary)
    {
        $this->ovhSubsidiary = $ovhSubsidiary;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }


    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @param string $serviceName
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    }


    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    public function getProductID()
    {
        return $this->productID;
    }

    /**
     * @param mixed $productID
     */
    public function setProductID($productID)
    {
        $this->productID = $productID;
    }

    /**
     * @return mixed
     */
    public function getHostingID()
    {
        return $this->hostingID;
    }

    /**
     * @param mixed $hostingID
     */
    public function setHostingID($hostingID)
    {
        $this->hostingID = $hostingID;
    }

    /**
     * @return mixed
     */
    public function getWhmcsServerID()
    {
        return $this->whmcsServerID;
    }

    /**
     * @param mixed $whmcsServerID
     */
    public function setWhmcsServerID($whmcsServerID)
    {
        $this->whmcsServerID = $whmcsServerID;
    }


    /**
     * @return mixed
     */
    public function getApiConsumer()
    {
        return $this->apiConsumer;
    }

    /**
     * @param mixed $apiConsumer
     */
    public function setApiConsumer($apiConsumer)
    {
        $this->apiConsumer = $apiConsumer;
    }


    /**
     * @return mixed
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @param mixed $apiToken
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * @return mixed
     */
    public function getApiUser()
    {
        return $this->apiUser;
    }

    /**
     * @param mixed $apiUser
     */
    public function setApiUser($apiUser)
    {
        $this->apiUser = $apiUser;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    private function loadData()
    {
        foreach ($this->params as $key => $param)
        {
            if (property_exists($this, $key) && $this->{$key} == null)
            {
                $this->{$key} = $param;
            }
        }
    }


}
