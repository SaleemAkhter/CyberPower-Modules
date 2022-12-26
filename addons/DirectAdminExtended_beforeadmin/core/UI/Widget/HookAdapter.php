<?php

namespace ModulesGarden\DirectAdminExtended\Core\UI\Widget;

use ModulesGarden\DirectAdminExtended\Core\UI\Builder\BaseContainer;

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