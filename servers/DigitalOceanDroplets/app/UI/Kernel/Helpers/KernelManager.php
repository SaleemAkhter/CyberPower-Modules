<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Kernel\Helpers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class KernelManager
{

    protected $params = [];

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function getDroplet()
    {
        $api = new Api($this->params);
        return $api->droplet->one();
    }
    public function getAvailableKernel()
    {
        $vm = $this->getDroplet();

        return $this->preparePrettyTable($vm->getAvailableKernels());

    }
    public function changeKernel($kernelID){
        $vm = $this->getDroplet();
        $kernelID = (int) $kernelID;
        return $vm->changeKernel($kernelID);

    }
    public function preparePrettyTable($itemList)
    {
        $allKernels = [];

        foreach ($itemList as $item)
        {

            $allKernels[] = [
                'id'   => $item->id,
                'name' => $item->name,
            ];
        }
        return $allKernels;
    }

}