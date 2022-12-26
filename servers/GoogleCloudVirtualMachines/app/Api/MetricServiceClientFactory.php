<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\WhmcsParams;

class MetricServiceClientFactory
{
    use WhmcsParams;

    /**
     * @return array
     */
    public function fromParams()
    {


        if (!$this->getWhmcsParamByKey('serveraccesshash')) {
            throw new \InvalidArgumentException("Server Access Hash is empty.");
        }
        $config = $this->getWhmcsParamByKey('serveraccesshash');
        $config = json_decode(htmlspecialchars_decode($config), true);

        return [
            'credentials' => $config
        ];
    }
}