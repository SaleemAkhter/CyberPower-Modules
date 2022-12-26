<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;

/**
 * BaseField controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseField extends BaseContainer
{

    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Field;
    
    protected $id    = 'baseField';
    protected $name  = 'baseField';
    protected $class = ['lu-form-check lu-m-b-2x'];
}
