<?php

namespace ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Html Data Response
 */
class HtmlDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'htmlData';
}
