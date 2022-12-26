<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\FieldsProvider;

/**
 * Description of Client
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Client
{

    private $apiToken;
    private $url = "https://api.digitalocean.com/v2/";
    private $domain;
    private $productID;
    private $hostingID;
    private $whmcsServerID;
    //Product Configuration
    private $project;
    private $region;
    private $image;
    private $size;
    private $backups;
    private $ipv6;
    private $volume;
    private $tags = [];
    private $userData;
    private $sshKey;
    private $sshKeys = [];
    private $privateNetworking;
    private $monitoring;
    private $snapshotLimit;
    private $randomDomainPrefix;
    private $firewallPrefix;
    //CustomFields
    private $serverID;
    private $firewalls;
    private $firewallsLimit;
    private $params;

    public function __construct($params)
    {
        $this->params = $params;
        if ($params['packageid'])
        {
            $product = new FieldsProvider($params['packageid']);

            $this->project           = $product->getField('project', 0);
            $this->region            = isset($params['configoptions']['region']) ? $params['configoptions']['region'] : $product->getField('region');
            $this->image             = isset($params['configoptions']['image']) ? $params['configoptions']['image'] : $product->getField('image');
            $this->size              = isset($params['configoptions']['size']) ? $params['configoptions']['size'] : $product->getField('size');
            $this->backups           = $this->checkTickBox(isset($params['configoptions']['backups']) ? $params['configoptions']['backups'] : $product->getField('backup', 'false'));
            $this->ipv6              = $this->checkTickBox(isset($params['configoptions']['ipv6']) ? $params['configoptions']['ipv6'] : $product->getField('ipv6', 'false'));
            $this->volume            = isset($params['configoptions']['volume']) ? $params['configoptions']['volume'] : $product->getField('volume');
            $this->tags              = (!empty($product->getField('tags'))) ? explode(',', $product->getField('tags')) : "";
            $this->userData          = isset($params['configoptions']['userData']) ? $params['configoptions']['userData'] : $product->getField('userData', 0);
            $this->sshKey            = $params['customfields']['sshKey'];
            $this->monitoring        = $this->checkTickBox(isset($params['configoptions']['monitoring']) ? $params['configoptions']['monitoring'] : $product->getField('monitoring', 'false'));
            $this->privateNetworking = $this->checkTickBox(isset($params['configoptions']['privateNetwork']) ? $params['configoptions']['privateNetwork'] : $product->getField('privateNetwork', 'false'));
            $this->snapshotLimit     = (isset($params['configoptions']['snapshots'])) ? $params['configoptions']['snapshots'] : $product->getField('snapshotLimit');
            $this->firewallsLimit    = (isset($params['configoptions']['firewalls'])) ? $params['configoptions']['firewalls'] : $product->getField('firewallLimit');
            $this->randomDomainPrefix = $product->getField('randomDomainPrefix');
            $this->firewallPrefix    = $product->getField('firewallPrefix');

            if($product->getField('sshkey'))
            {
                $this->sshKeys[] = $product->getField('sshkey');
            }
        }
        $this->whmcsServerID = $params['serverid'];
        $this->domain        = (!empty($params['domain']) ? $params['domain'] : $this->randomDomainPrefix . uniqid());
        $this->serverID      = $params['customfields']['serverID'];
        $this->apiToken      = $params['serverpassword'];
        $this->productID     = $params['packageid'];
        $this->hostingID     = $params['serviceid'];
        $this->firewalls = (!empty($params['customfields']['firewalls'])) ? explode(',', $params['customfields']['firewalls']) : [];

    }

    private function checkTickBox($field)
    {
        switch ($field)
        {
            case '0':
                return false;
            case 'on':
                return true;
            case '1':
                return true;
            default:
                return false;
        }
    }

    public function getApiToken()
    {
        return $this->apiToken;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getBackups()
    {
        return $this->backups;
    }

    public function getIpv6()
    {
        return $this->ipv6;
    }

    public function getVolume()
    {
        return $this->volume;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getUserData()
    {
        return $this->userData;
    }

    public function getSshKey()
    {
        return $this->sshKey;
    }

    public function getSshKeys()
    {
        return $this->sshKeys;
    }

    public function getPrivateNetworking()
    {
        return $this->privateNetworking;
    }

    public function getMonitoring()
    {
        return $this->monitoring;
    }

    public function getServerID()
    {
        return $this->serverID;
    }

    public function getSnapshotLimit()
    {
        return $this->snapshotLimit;
    }

    public function getProductID()
    {
        return $this->productID;
    }

    public function getHostingID()
    {
        return $this->hostingID;
    }

    function getWhmcsServerID()
    {
        return $this->whmcsServerID;
    }

    /**
     * @return mixed
     */
    public function getFirewalls()
    {
        return $this->firewalls;
    }

    /**
     * @return string
     */
    public function getFirewallsLimit()
    {
        return $this->firewallsLimit;
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return mixed|string
     */
    public function getFirewallPrefix(): string
    {
        return $this->firewallPrefix;
    }

}
