<?php

namespace ModulesGarden\Servers\HetznerVps\Core\UI\Traits;

/**
 * Ajax Components related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait AjaxComponent
{
    public function isAjaxComponent()
    {
        return $this instanceof \ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AjaxElementInterface;
    }
}
