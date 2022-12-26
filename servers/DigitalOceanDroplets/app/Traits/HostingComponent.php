<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Traits;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Models;

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
        $this->hosting = \ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Hosting::where('id', $id)->first();
    }

}
