<?php

namespace ModulesGarden\Servers\VultrVps\Core\UI\Traits;

/**
 * Ajax Components related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait AjaxComponent
{
    public function isAjaxComponent()
    {
        return $this instanceof \ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AjaxElementInterface;
    }
}
