<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates;


/**
 *  Abstract Ajax Response Model
 */
class RawClientAreaResponse
{
    protected $data = [];
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function getData()
    {
        return $this->data;
    }
}
