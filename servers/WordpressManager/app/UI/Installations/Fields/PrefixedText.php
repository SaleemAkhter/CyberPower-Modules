<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Fields;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\BaseField;
/**
 * BaseField controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class PrefixedText extends BaseField
{
    protected $id   = 'text';
    protected $name = 'text';
    protected $formgroupclass="";
    protected $prefix='';

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
    public function setPrefix($prefix)
    {
         $this->prefix=$prefix;
         return $this;
    }
    public function getPrefix()
    {
        return $this->prefix;
    }
}
