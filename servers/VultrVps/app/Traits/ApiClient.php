<?php


namespace ModulesGarden\Servers\VultrVps\App\Traits;


use ModulesGarden\Servers\VultrVps\App\Api\ApiClientFactory;

trait ApiClient
{

    /**
     * @var \ModulesGarden\Servers\VultrVps\App\Api\ApiClient
     */
    protected $apiClient;

    /**
     * @return \ModulesGarden\Servers\VultrVps\App\Api\ApiClient
     */
    public function apiClient()
    {
        if (empty($this->apiClient))
        {
            $this->apiClient = (new ApiClientFactory())->fromWhmcsParams();
        }
        return $this->apiClient;
    }
}