<?php

namespace ModulesGarden\DirectAdminExtended\Core\UI\ResponseTemplates;

use \ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\ResponseInterface;

/**
 * Ajax Raw Data Json Response
 */
class RawDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'rawData';
}
