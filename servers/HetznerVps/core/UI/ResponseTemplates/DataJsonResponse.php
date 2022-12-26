<?php

namespace ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Json Data Response
 */
class DataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'data';
}
