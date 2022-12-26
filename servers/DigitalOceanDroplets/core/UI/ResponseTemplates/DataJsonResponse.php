<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Json Data Response
 */
class DataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'data';
}
