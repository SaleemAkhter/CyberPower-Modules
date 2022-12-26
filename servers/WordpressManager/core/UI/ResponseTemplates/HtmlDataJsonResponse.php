<?php

namespace ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Html Data Response
 */
class HtmlDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'htmlData';
}
