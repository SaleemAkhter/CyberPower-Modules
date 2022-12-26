<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleClientFactory;

trait ApiClient
{

    /**
     * @var \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ApiClient
     */
    protected $apiClient;

    /**
     * @return \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ApiClient
     */
    public function apiClient(){
        if(empty($this->apiClient)){
            $this->apiClient = (new GoogleClientFactory())->fromParams();
            $this->apiClient->token();
        }
        return $this->apiClient;
    }
}