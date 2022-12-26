<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models;

use JsonSerializable;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Adapters\Client;

/**
 * Description of Serializer
 *
 * @author Artur Pilch
 */
class Serializer implements JsonSerializable
{
    public function fill($params)
    {
        foreach ($params as $key => $value)
        {
            if(property_exists($this, $key))
            {
                $this->$key = $value;
            }
        }
    }

    /**
     * Serialize object to JSON object
     * 
     * @return JSON object
     */
    public function jsonSerialize()
    {
        $out = [];


        foreach (get_class_vars(get_called_class()) as $property => $value)
        {
            if ($property == "params")
            {
                continue;
            }

            if (!isset($this->{$property}))
            {
                continue;
            }

            if (is_object($this->{$property}) && $this->{$property} instanceof JsonSerializable)
            {
                $out[$property] = $this->{$property}->jsonSerialize();
            }
            else
            {
                $out[$property] = $this->{$property};
            }
        }
        return $out;
    }

    /**
     * Serialize object to array
     * 
     * @return array
     */
    public function toArray()
    {
        $out = [];

        foreach (get_class_vars(get_called_class()) as $property => $value)
        {

            if ($property == "params")
            {
                continue;
            }

            if (!isset($this->{$property}))
            {
                continue;
            }
            if (is_object($this->$property))
            {
                $out[$property] = $this->{$property}->toArray();
            }
            else
            {
                $out[$property] = $this->{$property};
            }
        }
        return $out;
    }

}
