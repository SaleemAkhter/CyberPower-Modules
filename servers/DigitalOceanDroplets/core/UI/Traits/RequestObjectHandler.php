<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\Request;

/** 
 * Adds methods to handle requests data
 */
trait RequestObjectHandler
{
    /** 
     * request object variable
     * @var \ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\Request
     */
    protected $requestObj = null;
    
    /** 
     * loads request object
     */
    protected function loadRequestObj()
    {
        if ($this->requestObj === null)
        {
            $this->requestObj = Request::build();
        }
        
        return $this;
    }
    
    /** 
     * returns data from request by provided $key or dafault value if key was not found
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getRequestValue($key, $defaultValue = false)
    {
        if ($this->requestObj)
        {
            return $this->requestObj->get($key, $defaultValue);
        }
        
        return $defaultValue;
    }    
}
