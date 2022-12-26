<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;

class GoogleClientFactory
{

    use WhmcsParams;

    /**
     * @return \Google_Client
     * @throws \Google_Exception
     */
    public function fromParams(){
        if (!$this->getWhmcsParamByKey('serveraccesshash'))
        {
            throw new \InvalidArgumentException("Server Access Hash is empty.");
        }
        $config = $this->getWhmcsParamByKey('serveraccesshash');
        $config = json_decode(htmlspecialchars_decode($config), true);
        if (!$config || !is_array($config))
        {
            throw new \InvalidArgumentException('Invalid json for Server Access Hash');
        }
        $client = new GoogleClient();
        $client->setApplicationName("Client_Library_Examples");
        $client->setAuthConfig($config);
        $client->addScope(\Google_Service_Compute::CLOUD_PLATFORM);
        return $client;
    }


}