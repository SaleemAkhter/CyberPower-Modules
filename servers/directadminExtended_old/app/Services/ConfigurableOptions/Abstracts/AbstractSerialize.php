<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Services\ConfigurableOptions\Abstracts;

/**
 * Description of TypeConstans
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
abstract class AbstractSerialize
{

    protected function prepareName($key, $name = null)
    {
        if (is_null($name))
        {
            return $key . "|" . $key;
        }
        return $key . "|" . $name;
    }

    public function toArray()
    {
        $out = [];

        foreach (get_class_vars(get_called_class()) as $property => $value)
        {


            if (!isset($this->{$property}))
            {
                continue;
            }
            if (is_object($this->$property) || is_array($this->$property))
            {
                continue;
            }
            else
            {
                $out[$property] = $this->{$property};
            }
        }
        return $out;
    }

}
