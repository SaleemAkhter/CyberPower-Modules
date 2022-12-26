<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Html Data Response
 */
class HtmlDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'htmlData';
}
