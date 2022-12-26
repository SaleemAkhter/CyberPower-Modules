<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Html Data Response
 */
class HtmlDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'htmlData';
}
