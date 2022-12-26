<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits;

/**
 * Vue Components related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait VueComponent
{
    protected $vueComponent = false;
    protected static $vueComponentBody = '';
    protected static $listIdElements = [];


    public function hasVueComponents()
    {
        return $this->vueComponent;
    }
    
    public function getVueComponents()
    {
        if(self::$vueComponentBody === '')
        {
            $this->html = self::generate($this);
        }
        
        return self::$vueComponentBody;
    }
    
    protected function setVueComponents($componentBody, $id)
    {
        if (in_array($id, self::$listIdElements, true) === false)
        {
            self::$vueComponentBody .= $componentBody;
            self::$listIdElements[] = $id;
        }
        return $this;
    }
}
