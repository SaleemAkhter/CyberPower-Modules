<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Html Data Response
 */
class HtmlDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'htmlData';
}
