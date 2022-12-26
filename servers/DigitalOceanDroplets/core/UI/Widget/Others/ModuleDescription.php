<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Others;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;

/**
 * ModuleDescription
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ModuleDescription extends BaseContainer
{
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Description;
    
    protected $name  = 'moduleDescription';
    protected $id    = 'moduleDescription';
    protected $title = 'moduleDescription';
    protected $class = ['info'];
}
