<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\ResponseTemplates;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ResponseInterface;

/**
 *  Ajax Json Data Response
 */
class DataJsonResponse extends Response implements ResponseInterface
{
    protected $dataType = 'data';
}
