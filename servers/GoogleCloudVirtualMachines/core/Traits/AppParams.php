<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Traits;

use function ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\di;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\ServiceLocator;

/**
 * Description of AppParams
 *
 * @author INBSX-37H
 */
trait AppParams
{
    /** 
     *
     * @var type \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\AppParamsContainer
     */
    protected $appParams = null;    
    
    public function initParams()
    {
        if ($this->appParams === null)
        {
            $this->appParams = di('appParamsContainer');
        }
    }
    
    public function setAppParam($key = null, $value = null)
    {   
        $this->initParams();
        
        $this->appParams->setParam($key, $value);
    }
    
    public function getAppParams()
    {
        $this->initParams();
        
        return $this->appParams->getParams();
    }
    
    public function getAppParam($key, $default = null)
    {
        $this->initParams();
        
        return $this->appParams->getParam($key, $default);
    }    
}
