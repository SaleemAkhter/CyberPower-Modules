<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Widget;

use ModulesGarden\Servers\AwsEc2\Core\UI\Builder\BaseContainer;

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