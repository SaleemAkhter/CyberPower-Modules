<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model;

abstract class MAbstract
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $val)
        {
            if (!property_exists($this, $key))
            {
                continue;
            }
            $this->$key = $val;
        }
    }
    
    public function loadAdditionalOptions(array $options)
    {
        $objectValues = get_object_vars($this);
        foreach ($options as $key => $val)
        {
            if (in_array($val, $objectValues))
            {
                continue;
            }
            $this->additionalOptions[$key] = $val;
        }
    }

}
