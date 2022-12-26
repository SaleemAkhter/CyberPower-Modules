<?php

class AbstractControllerModel 
{
    protected $name = "Home";
    protected $namespace = "ModulesGarden\Servers\HetznerVps\App\Http\\";
    protected $abstractNamespace = "\ModulesGarden\Servers\HetznerVps\Core\Http\\";
    protected $use = "use ModulesGarden\Servers\HetznerVps\Core\Helper;";
    protected static $isAdmin = false;
   
    
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function setIsAdmin($isAdmin = true)
    {
        if($isAdmin)
        {
            $this->namespace .= "Admin";
            $this->abstractNamespace .= "AbstractController";
        }
        else
        {
            $this->namespace .= "Client";
            $this->abstractNamespace .= "AbstractClientController";
        }
        
        self::$isAdmin = $isAdmin;
        
        return $this;
    }
   
    public function renderFile($path)
    {
        $fileText = $this->readClass();
        
        foreach ($this as $key => $value)
        {
            if (property_exists($this,$key))
            {
                $fileText = str_replace("#{$key}#", $value, $fileText);
            }
        }
                
        if (self::$isAdmin)
        {
            $type = 'Admin';
        }
        else
        {
            $type = 'Client';
        }
        $path = $path . $type . DS . $this->name . ".php";
        if (file_exists($path))
        {
            return "File exists.";
        }
        else
        {
            try
            {
                file_put_contents($path,$fileText);
            }
            catch (Exception $exc)
            {
                return $exc->getMessage();
            }
            
            return "The controller ('{$this->name}') was successfully generated.";
        }
    }

    
    protected function readClass()
    {
        return $this->readFile('class');
    }
    
    protected function readFile($type)
    {
        return file_get_contents(__DIR__ . DS . $type);
    }
    
}
