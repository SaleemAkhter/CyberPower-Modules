<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleClientFactory;

trait ComputeTrait
{

    /**
     * @var \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ApiClient
     */
    protected $compute;

    /**
     * @return \Google_Service_Compute
     */
    public function compute(){
        if(empty($this->compute)){
            $client =  (new GoogleClientFactory())->fromParams();
            $this->compute = new \Google_Service_Compute($client);
        }
        return $this->compute;
    }
}