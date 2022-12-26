<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Builder\BaseContainer;

/**
 * BaseField controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseField extends BaseContainer
{

    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\Field;
    
    protected $id    = 'baseField';
    protected $name  = 'baseField';
    protected $class = ['lu-form-check lu-m-b-2x'];
}
