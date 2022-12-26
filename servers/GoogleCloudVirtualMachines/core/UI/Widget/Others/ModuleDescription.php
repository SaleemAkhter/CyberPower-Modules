<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Others;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Builder\BaseContainer;

/**
 * ModuleDescription
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ModuleDescription extends BaseContainer
{
    use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Traits\Description;
    
    protected $name  = 'moduleDescription';
    protected $id    = 'moduleDescription';
    protected $title = 'moduleDescription';
    protected $class = ['info'];
}
