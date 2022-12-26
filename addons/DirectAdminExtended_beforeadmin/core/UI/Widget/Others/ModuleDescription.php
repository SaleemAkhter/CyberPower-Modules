<?php

namespace ModulesGarden\DirectAdminExtended\Core\UI\Widget\Others;

use ModulesGarden\DirectAdminExtended\Core\UI\Builder\BaseContainer;

/**
 * ModuleDescription
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ModuleDescription extends BaseContainer
{
    use \ModulesGarden\DirectAdminExtended\Core\UI\Traits\Description;
    
    protected $name  = 'moduleDescription';
    protected $id    = 'moduleDescription';
    protected $title = 'moduleDescription';
    protected $class = ['info'];
}
