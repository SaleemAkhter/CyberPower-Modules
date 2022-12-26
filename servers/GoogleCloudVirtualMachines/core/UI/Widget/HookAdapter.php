<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Builder\BaseContainer;

class HookAdapter extends BaseContainer
{
    protected $name = 'hookAdapter';
    protected $data = [];
    protected $adaptId = '';

    public function adapt()
    {
        return $this->adaptId;
    }
}