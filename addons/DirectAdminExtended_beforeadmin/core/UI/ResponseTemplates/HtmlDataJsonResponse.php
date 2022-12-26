<?php

namespace ModulesGarden\DirectAdminExtended\Core\UI\ResponseTemplates;

use \ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Html Data Response
 */
class HtmlDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'htmlData';
}
