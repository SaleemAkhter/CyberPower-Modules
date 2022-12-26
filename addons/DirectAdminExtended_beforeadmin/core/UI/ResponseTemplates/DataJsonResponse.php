<?php

namespace ModulesGarden\DirectAdminExtended\Core\UI\ResponseTemplates;

use \ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Json Data Response
 */
class DataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'data';
}
