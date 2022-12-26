<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\App;

class AppParamsContainer
{
    /**
     * @var array
     * params container
     */
    protected $params = [];
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function getParam($key, $default = null)
    {
        if (isset($this->params[$key]))
        {
            return $this->params[$key];
        }
        
        return $default;
    }
    
    public function setParam($key, $value = null)
    {
        $this->params[$key] = $value;
        
        return $this;
    }
}
