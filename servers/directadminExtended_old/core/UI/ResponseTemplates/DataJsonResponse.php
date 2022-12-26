<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Json Data Response
 */
class DataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'data';
}
