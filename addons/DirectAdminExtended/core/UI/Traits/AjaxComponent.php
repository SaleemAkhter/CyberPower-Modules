<?php

namespace ModulesGarden\DirectAdminExtended\Core\UI\Traits;

/**
 * Ajax Components related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait AjaxComponent
{
    public function isAjaxComponent()
    {
        return $this instanceof \ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface;
    }
}
