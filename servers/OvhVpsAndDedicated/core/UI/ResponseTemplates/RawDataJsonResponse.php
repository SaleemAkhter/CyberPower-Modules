<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ResponseInterface;

/**
 * Ajax Raw Data Json Response
 */
class RawDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'rawData';
}
