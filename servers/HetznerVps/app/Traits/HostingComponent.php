<?php

namespace ModulesGarden\Servers\HetznerVps\App\Traits;

use ModulesGarden\Servers\HetznerVps\App\Models;

/**
 * HostingComponent trait
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait HostingComponent
{

    protected $hosting = null;

    public function initHosting($id)
    {
        $this->hosting = \ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\Hosting::where('id', $id)->first();
    }

}
