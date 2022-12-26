<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Builder\BaseContainer;

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