<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Json Data Response
 */
class DataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'data';
}
