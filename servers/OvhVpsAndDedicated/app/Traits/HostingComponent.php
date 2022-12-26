<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits;

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
        $this->hosting = \ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Whmcs\Hosting::where('id', $id)->first();
    }

}
