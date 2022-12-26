<?php

namespace ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ResponseInterface;

/**
 * Ajax Raw Data Json Response
 */
class RawDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'rawData';
}
