<?php

namespace ModulesGarden\WordpressManager\Core\UI\Widget;

use ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;

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