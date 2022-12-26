<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models;

use JsonSerializable;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters\Client;

/**
 * Description of Serializer
 *
 * @author Mateusz Pawłowski
 */
class Serializer implements JsonSerializable
{
    /*
     * @var \ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Adapters\Client;
     */

    protected $params;

    public function __construct($params = null)
    {
        if (!is_null($params))
        {
            $this->params = new Client($params);
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
