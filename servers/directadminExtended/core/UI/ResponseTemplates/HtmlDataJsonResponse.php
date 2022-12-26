<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Html Data Response
 */
class HtmlDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'htmlData';
}
