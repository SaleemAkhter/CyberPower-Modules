<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ResponseInterface;

/**
 * Ajax Raw Data Json Response
 */
class RawDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'rawData';
}
