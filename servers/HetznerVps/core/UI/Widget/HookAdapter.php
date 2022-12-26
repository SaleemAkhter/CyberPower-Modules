<?php

namespace ModulesGarden\Servers\HetznerVps\Core\UI\Widget;

use ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer;

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