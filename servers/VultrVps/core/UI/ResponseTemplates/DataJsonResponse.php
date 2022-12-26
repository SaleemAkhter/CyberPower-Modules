<?php

namespace ModulesGarden\Servers\VultrVps\Core\UI\ResponseTemplates;

use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Json Data Response
 */
class DataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'data';
}
