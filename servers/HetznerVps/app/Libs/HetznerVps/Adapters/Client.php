<?php

namespace ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Adapters;

use ModulesGarden\Servers\HetznerVps\App\Enum\ConfigurableOption;
use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;

/**
 * Description of Client
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Client
{

    private $apiToken;
    private $url  = "https://api.hetzner.cloud/v1/";
    private $domain;
    private $productID;
    private $hostingID;
    private $whmcsServerID;
    //Product Configuration
    private $location;
    private $datacenter;
    private $image;
    private $type;
    private $backups;
    private $volume;
    private $userData;
    private $ips;
    private $randomDomainPrefix;
    private $enableBackups;
    private $firewallsLimit;
    private $firewallsRules;
    private $firewallsInbound;
    private $firewallsOutbound;
    //CustomFields
    private $serverID;
    private $sshKey;


    public function __construct($params)
    {
        if ($params['packageid'])
        {
            $product = new FieldsProvider($params['packageid']);

            $this->location          = isset($params['configoptions']['location']) ? $params['configoptions']['location'] : $product->getField('location');
            $this->datacenter        = isset($params['configoptions']['datacenter']) ? $params['configoptions']['datacenter'] : $product->getField('datacenter');
            $this->image             = isset($params['configoptions']['image']) ? $params['configoptions']['image'] : $product->getField('image');
            $this->type              = isset($params['configoptions']['type']) ? $params['configoptions']['type'] : $product->getField('type');
            $this->backups           = $this->checkTickBox(isset($params['configoptions'][ConfigurableOption::BACKUPS]) ? $params['configoptions'][ConfigurableOption::BACKUPS] : $product->getField('enableBackups', 0));
            $this->volume            = isset($params['configoptions']['volume']) ? $params['configoptions']['volume'] : $product->getField('volume');
            $this->userData          = isset($params['configoptions']['userData']) ? $params['configoptions']['userData'] : $product->getField('userData', 0);
            $this->ips               = isset($params['configoptions']['numberOfFloatingIps']) ? $params['configoptions']['numberOfFloatingIps'] : $product->getField('floatingIpsNumber', 0);
            $this->randomDomainPrefix= isset($params['configoptions']['randomDomainPrefix']) ? $params['configoptions']['randomDomainPrefix'] : $product->getField('randomDomainPrefix', 0);
            $this->enableBackups     = isset($params['configoptions'][ConfigurableOption::BACKUPS]) ? $params['configoptions'][ConfigurableOption::BACKUPS] : $product->getField('enableBackups', 0);
            $this->firewallsLimit    = isset($params['configoptions']['firewallsLimitNumber']) ? $params['configoptions']['firewallsLimitNumber'] : $product->getField('firewallsLimitNumber', 0);
            $this->firewallsRules    = isset($params['configoptions']['firewallTotalRulesLimitNumber']) ? $params['configoptions']['firewallTotalRulesLimitNumber'] : $product->getField('firewallTotalRulesLimitNumber', 0);
            $this->firewallsInbound  = isset($params['configoptions']['firewallInboundRulesNumber']) ? $params['configoptions']['firewallInboundRulesNumber'] : $product->getField('firewallInboundRulesNumber', 0);
            $this->firewallsOutbound = isset($params['configoptions']['firewallOutboundRulesNumber']) ? $params['configoptions']['firewallOutboundRulesNumber'] : $product->getField('firewallOutboundRulesNumber', 0);
        }
        $this->whmcsServerID = $params['serverid'];
        $this->domain        = (!empty($params['domain']) ? $params['domain'] : $this->randomDomainPrefix . uniqid());
        $this->serverID      = $params['customfields']['serverID'];
        $this->sshKey        = $params['customfields']['sshkeys'];
        $this->apiToken      = $params['serverpassword'];
        $this->productID     = $params['packageid'];
        $this->hostingID     = $params['serviceid'];

    }

    private function checkTickBox($field)
    {
        switch ($field)
        {
            case 'on':
            case '1':
                return true;
            default:
                return false;
        }
    }

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return mixed
     */
    public function getProductID()
    {
        return $this->productID;
    }

    /**
     * @return mixed
     */
    public function getHostingID()
    {
        return $this->hostingID;
    }

    /**
     * @return mixed
     */
    public function getWhmcsServerID()
    {
        return $this->whmcsServerID;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isBackups()
    {
        return $this->backups;
    }

    /**
     * @return integer
     */
    public function getVolume()
    {
        return (int) $this->volume;
    }

    /**
     * @return integer
     */
    public function getServerID()
    {
        return (int) $this->serverID;
    }

    /**
     * @return integer
     */
    public function getDatacenter()
    {
        return (int) $this->datacenter;
    }

    /**
     * @return mixed
     */
    public function getSshKey()
    {
        return $this->sshKey;
    }

    /**
     * @return mixed
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * @return mixed|string
     */
    public function getIps()
    {
        return $this->ips;
    }

    /**
     * @return mixed|string
     */
    public function getRandomDomainPrefix()
    {
        return $this->randomDomainPrefix;
    }

    /**
     * @return mixed|string
     */
    public function areBackupsEnabled()
    {
        return $this->enableBackups;
    }

}
