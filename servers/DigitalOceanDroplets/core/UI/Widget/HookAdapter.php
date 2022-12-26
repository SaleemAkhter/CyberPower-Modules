<?php
/**
 * User: inbs
 * Date: 11.01.18
 * Time: 15:53
 */

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;

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