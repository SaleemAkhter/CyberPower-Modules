<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ResponseInterface;

/**
 * Ajax Raw Data Json Response
 */
class RawDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'rawData';
}
