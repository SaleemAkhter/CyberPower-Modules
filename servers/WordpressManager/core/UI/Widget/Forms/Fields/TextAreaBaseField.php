<?php

namespace ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;

use ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;

class TextAreaBaseField extends BaseContainer
{

    use \ModulesGarden\WordpressManager\Core\UI\Traits\Field;
    
    protected $id    = 'baseField';
    protected $name  = 'baseField';
    protected $class = ['lu-form-check lu-m-b-2x'];

}
