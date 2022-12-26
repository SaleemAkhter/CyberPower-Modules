<?php

namespace ModulesGarden\Servers\VultrVps\Core\UI\ResponseTemplates;

use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Html Data Response
 */
class HtmlDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'htmlData';
}
