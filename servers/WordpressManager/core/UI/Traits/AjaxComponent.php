<?php

namespace ModulesGarden\WordpressManager\Core\UI\Traits;

/**
 * Ajax Components related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait AjaxComponent
{
    public function isAjaxComponent()
    {
        return $this instanceof \ModulesGarden\WordpressManager\Core\UI\Interfaces\AjaxElementInterface;
    }
}
