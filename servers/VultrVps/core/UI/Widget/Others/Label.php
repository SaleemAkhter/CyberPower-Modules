<?php

namespace ModulesGarden\Servers\VultrVps\Core\UI\Widget\Others;

use ModulesGarden\Servers\VultrVps\Core\UI\Builder\BaseContainer;

/**
 * ModuleDescription
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Label extends BaseContainer
{
    protected $name  = 'mgLabel';
    protected $id    = 'mgLabel';
    protected $title = 'mgLabel';
    protected $class = ['lu-label'];

    protected $message = null;
    protected $color = 'FFFFFF';
    protected $backgroundColor = 'FFFFFF';
 
    public function setMessage($message)
    {
        $this->message = $message;
        
        return $this;
    }
    
    public function getMessage()
    {
        return $this->message;
    }  
    
    public function setColor($color)
    {
        $this->color = $color;
        
        return $this;
    }

    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
        
        return $this;
    }    
    
    public function getColor()
    {
        return $this->color;
    }  

    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }      
}
