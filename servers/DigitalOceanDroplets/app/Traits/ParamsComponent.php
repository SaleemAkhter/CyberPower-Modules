<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Traits;

/**
 * HostingComponent trait
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait ParamsComponent
{

    protected $params = [];

    public function setParams($params = null)
    {
        $this->params = $params;
    }

}
