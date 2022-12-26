<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models;

class AbstractModel
{

    protected $additionalOptions = []; 
    
    public function __construct($params = [])
    {
        if ($params)
        {
            $this->fill($params);
        }
    }

    public function fill($data)
    {
        foreach ($data as $key => $val)
        {
            if (!property_exists($this, $key))
            {
                //continue;
                //throw new \Exception("Model: Something went wrong with filling data[$key].");
                continue;
            }

            $this->$key = $val;
        }
    }

    public function buildArrayFromSelf()
    {
        $arr = array_filter(get_object_vars($this));
        $out = [];
        foreach ($arr as $key => $val)
        {
            if ($val == 'off')
            {
                $val = 0;
            }
            elseif ($val == 'on')
            {
                $val = 1;
            }

            $out[$key] = $val;
        }

        return $out;
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
            if(strpos($key, 'domain') !== false)
            {
                $this->domain = $val;
            }
            $this->additionalOptions[$key] = $val;
        }
    }
}
