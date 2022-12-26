<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\UI\ResponseTemplates;

use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Json Data Response
 */
class DataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'data';
}
