<?php

namespace ModulesGarden\Servers\AwsEc2\Core\App;

use ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Instances\Addon\Config;
use ModulesGarden\Servers\AwsEc2\Core\ModuleConstants;
use ModulesGarden\Servers\AwsEc2\Core\ServiceLocator;

class AppParamsContainer
{
    /**
     * @var array
     * params container
     */
    protected $params = [];

    public function __construct()
    {
        $addonConfig = ServiceLocator::call(Config::class);
        $params = $addonConfig->getConfig();

        if (is_array($params))
        {
            $this->params = $params;
        }

        $this->params['moduleAppType'] = ModuleConstants::getModuleType();
    }

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
