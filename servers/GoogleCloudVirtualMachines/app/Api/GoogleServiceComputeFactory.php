<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Enum\CustomField;

class GoogleServiceComputeFactory
{

    /**
     * @return \Google_Service_Compute
     * @throws \Google_Exception
     */
    public function fromParams(){
        $client = (new GoogleClientFactory())->fromParams();
        return new \Google_Service_Compute($client);
    }
}