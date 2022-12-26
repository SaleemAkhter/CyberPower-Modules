<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Traits;

/**
 * Ajax Components related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait AjaxComponent
{
    public function isAjaxComponent()
    {
        return $this instanceof \ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AjaxElementInterface;
    }
}
