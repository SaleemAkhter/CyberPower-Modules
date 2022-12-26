<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleClientFactory;

trait GoogleServiceComputeTrait
{

    /**
     * @var \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ApiClient
     */
    protected $googleServiceCompute;

    /**
     * @return \Google_Service_Compute
     */
    public function googleServiceCompute(){
        if(empty($this->googleServiceCompute)){
            $client =  (new GoogleClientFactory())->fromParams();
            $this->googleServiceCompute = new \Google_Service_Compute($client);
        }
        return $this->googleServiceCompute;
    }
}