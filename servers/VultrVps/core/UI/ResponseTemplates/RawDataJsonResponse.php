<?php

namespace ModulesGarden\Servers\VultrVps\Core\UI\ResponseTemplates;

use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ResponseInterface;

/**
 * Ajax Raw Data Json Response
 */
class RawDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'rawData';
}
