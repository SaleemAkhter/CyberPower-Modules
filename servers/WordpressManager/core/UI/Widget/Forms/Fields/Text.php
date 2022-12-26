<?php

namespace ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;

/**
 * BaseField controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Text extends BaseField
{
    protected $id   = 'text';
    protected $name = 'text';
    protected $formgroupclass="";

    public function getFormGroupClass()
    {
        return $this->formgroupclass;
    }
    public function setFormGroupClass($class)
    {
        if(is_array($class)){
            $class=implode(" ",$class);
        }
        $this->formgroupclass=$class;
        return $this;
    }
}
