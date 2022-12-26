<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Html Data Response
 */
class HtmlDataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'htmlData';
}
