<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\UI\ResponseTemplates;

use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\ResponseInterface;

/**
 * Ajax Raw Data Json Response
 */
class RawDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'rawData';
}
