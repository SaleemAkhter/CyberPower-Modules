<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Traits;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Models;

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
        $this->hosting = \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Models\Whmcs\Hosting::where('id', $id)->first();
    }

}
