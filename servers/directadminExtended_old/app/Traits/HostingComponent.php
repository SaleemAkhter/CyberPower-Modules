<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Traits;

use \ModulesGarden\Servers\DirectAdminExtended\App\Models;

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
        $this->hosting = Models\Hosting::factory($id);
    }
}
