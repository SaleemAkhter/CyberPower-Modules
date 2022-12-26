<?php

namespace ModulesGarden\Servers\HetznerVps\App\Traits;

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
